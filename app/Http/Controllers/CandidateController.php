<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Department;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use App\Models\RecommendedAction;
use Illuminate\Support\Facades\DB;

class CandidateController extends Controller
{

    public function interview(Candidate $candidate)
    {
        try {
            if (auth()->user()->department_id != $candidate->department_id && !auth()->user()->hasRole('admin') && !auth()->user()->hasRole('hr')) {
                return redirect()->route('candidates.index')
                    ->with('error', 'Không thể xem trang này!');
            }

            // Lấy dữ liệu đánh giá từ bảng evaluations
            $evaluations = [];
            $candidate->evaluations = $candidate->evaluations ?? collect();

            foreach ($candidate->evaluations as $evaluation) {
                $evaluations[$evaluation->role][$evaluation->criteria] = $evaluation->rating;
            }

            // Lấy dữ liệu đề xuất từ bảng recommended_actions
            $recommendations = [];
            $candidate->recommendations = $candidate->recommendations ?? collect();

            foreach ($candidate->recommendations as $recommendation) {
                $recommendations[$recommendation->role] = [
                    'action' => $recommendation->action,
                    'propose_next_step' => $recommendation->propose_next_step
                ];
            }

            // Tính tổng điểm cho mỗi vai trò
            $totalScores = [
                'hr' => $candidate->evaluations->where('role', 'hr')->sum('rating'),
                'lm' => $candidate->evaluations->where('role', 'lm')->sum('rating'),
                'final' => $candidate->evaluations->where('role', 'final')->sum('rating')
            ];

            // Tính điểm trung bình
            $validScores = collect($totalScores)->filter(function ($score) {
                return $score > 0;
            });

            $averageScore = $validScores->count() > 0 ? round($validScores->sum() / $validScores->count()) : 0;

            return view('admin.pages.interview', compact(
                'candidate',
                'evaluations',
                'recommendations',
                'totalScores',
                'averageScore'
            ));
        } catch (\Exception $e) {
            return redirect()->route('candidates.index')
                ->with('error', 'Không thể xem trang này! ' . $e->getMessage());
        }
    }

    /**
     * Xác định vai trò của người dùng trong quy trình phỏng vấn
     */
    private function determineInterviewRole($user, $candidate)
    {
        if ($user->hasRole('admin')) {
            return 'admin'; // Admin có thể xem tất cả
        } elseif ($user->hasRole('hr')) {
            return 'hr'; // HR chỉ thấy điểm HR
        } elseif (
            $user->name == $candidate->lm_name ||
            ($user->department_id == $candidate->department_id && $user->hasRole('manager'))
        ) {
            return 'lm'; // Line Manager thấy điểm LM và HR
        } elseif ($user->name == $candidate->final_name || $user->hasRole('director')) {
            return 'final'; // Final thấy điểm Final và HR
        }

        return 'viewer'; // Người dùng khác chỉ có thể xem, không thấy điểm
    }

    public function saveInterview(Request $request, Candidate $candidate)
    {
        try {
            DB::beginTransaction();

            // Xác định vai trò người dùng
            $userRole = auth()->user()->role;

            // Cập nhật thông tin cơ bản của ứng viên 
            // (tất cả vai trò đều có thể cập nhật thông tin cơ bản)
            $candidate->update([
                'full_name' => $request->full_name,
                'desired_position' => $request->desired_position,
                'outlet_department' => $request->outlet_department,
                'employment_type' => $request->employment_type,

                'can_work_holidays' => $request->has('can_work_holidays') ? $request->can_work_holidays : null,
                'can_work_different_shifts' => $request->has('can_work_different_shifts') ? $request->can_work_different_shifts : null,
                'can_work_split_shifts' => $request->has('can_work_split_shifts') ? $request->can_work_split_shifts : null,
                'can_work_overtime' => $request->has('can_work_overtime') ? $request->can_work_overtime : null,
                'can_work_late_shift' => $request->has('can_work_late_shift') ? $request->can_work_late_shift : null,

                'notice_days' => $request->notice_days,
                'available_date' => $request->available_date,
                'min_salary' => $request->min_salary,
            ]);

            // Cập nhật thông tin người phỏng vấn theo vai trò
            if ($userRole == 'hr') {
                $candidate->update([
                    'hr_name' => $request->hr_name,
                    'hr_date' => $request->hr_date,
                    'reference_feedback' => $request->reference_feedback
                ]);
            } elseif ($userRole == 'lm') {
                $candidate->update([
                    'lm_name' => $request->lm_name,
                    'lm_date' => $request->lm_date,
                    'reference_feedback' => $request->reference_feedback
                ]);
            } elseif ($userRole == 'final') {
                $candidate->update([
                    'final_name' => $request->final_name,
                    'final_date' => $request->final_date
                ]);
            } elseif ($userRole == 'admin') {
                // Admin có thể cập nhật tất cả
                $candidate->update([
                    'hr_name' => $request->hr_name,
                    'hr_date' => $request->hr_date,
                    'lm_name' => $request->lm_name,
                    'lm_date' => $request->lm_date,
                    'final_name' => $request->final_name,
                    'final_date' => $request->final_date,
                    'reference_feedback' => $request->reference_feedback
                ]);
            }

            // Danh sách tiêu chí đánh giá
            $criteriaList = [
                'appearance',
                'english',
                'chinese',
                'japanese',
                'computer',
                'behavior',
                'characteristics',
                'communication',
                'motivation',
                'experience',
                'customer',
                'flexibility',
                'teamwork'
            ];

            // Chỉ xóa và thêm đánh giá của vai trò hiện tại
            if ($userRole == 'hr' || $userRole == 'lm' || $userRole == 'final' || $userRole == 'admin') {
                // Nếu là admin thì có thể cập nhật mọi vai trò, ngược lại chỉ cập nhật vai trò của mình
                $rolesToUpdate = ($userRole == 'admin') ? ['hr', 'lm', 'final'] : [$userRole];

                foreach ($rolesToUpdate as $role) {
                    // Xóa đánh giá cũ của vai trò này
                    Evaluation::where('candidate_id', $candidate->id)
                        ->where('role', $role)
                        ->delete();

                    // Thêm đánh giá mới
                    foreach ($criteriaList as $criteria) {
                        $ratingField = "{$role}_rating_{$criteria}";

                        if ($request->has($ratingField) && !is_null($request->$ratingField)) {
                            Evaluation::create([
                                'candidate_id' => $candidate->id,
                                'role' => $role,
                                'criteria' => $criteria,
                                'rating' => $request->$ratingField
                            ]);
                        }
                    }

                    // Xóa đề xuất cũ của vai trò này
                    RecommendedAction::where('candidate_id', $candidate->id)
                        ->where('role', $role)
                        ->delete();

                    // Thêm đề xuất mới
                    $actionField = "action_{$role}";
                    $recommendationField = "{$role}_recommendation";

                    if (($request->has($actionField) && !empty($request->$actionField)) ||
                        ($request->has($recommendationField) && !empty($request->$recommendationField))
                    ) {

                        RecommendedAction::create([
                            'candidate_id' => $candidate->id,
                            'role' => $role,
                            'action' => $request->$actionField ?? '',
                            'propose_next_step' => $request->$recommendationField ?? ''
                        ]);
                    }
                }
            }

            DB::commit();

            // Tính lại tổng điểm sau khi lưu
            $totalScores = [
                'hr' => Evaluation::where('candidate_id', $candidate->id)->where('role', 'hr')->sum('rating'),
                'lm' => Evaluation::where('candidate_id', $candidate->id)->where('role', 'lm')->sum('rating'),
                'final' => Evaluation::where('candidate_id', $candidate->id)->where('role', 'final')->sum('rating')
            ];

            // Log kết quả đánh giá
            \Log::info('Đánh giá ứng viên ' . $candidate->full_name . ' đã được cập nhật bởi ' . auth()->user()->name);

            return redirect()->route('candidates.interview', $candidate->id)
                ->with('success', 'Đánh giá phỏng vấn đã được lưu thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Lỗi lưu đánh giá ứng viên: ' . $e->getMessage());
            return back()->with('error', 'Không thể lưu đánh giá: ' . $e->getMessage())->withInput();
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Candidate::query();

        // Xử lý tìm kiếm
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('desired_position', 'like', "%{$search}%")
                    ->orWhere('outlet_department', 'like', "%{$search}%");
            });
        }

        // Phân quyền: nếu không phải admin và hr thì chỉ xem được ứng viên cùng phòng ban
        if (auth()->user()->role != 'admin' && auth()->user()->role != 'hr') {
            $query->where('department_id', auth()->user()->department_id);
        }

        $candidates = $query->latest()->paginate(10);

        return view('admin.pages.candidates.index', compact('candidates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
        return view('admin.pages.candidates.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'desired_position' => 'required|string|max:255',
            'outlet_department' => 'required|string|max:255',
            'employment_type' => 'required|in:full-time,casual-labor',
            'department_id' => 'required|exists:departments,id',
        ], [
            'full_name.required' => 'Họ tên không được để trống',
            'desired_position.required' => 'Vị trí mong muốn không được để trống',
            'outlet_department.required' => 'Phòng ban không được để trống',
            'employment_type.required' => 'Loại hình công việc không được để trống',
            'employment_type.in' => 'Loại hình công việc không hợp lệ',
            'department_id.required' => 'Phòng ban không được để trống',
            'department_id.exists' => 'Phòng ban không tồn tại',
        ]);

        Candidate::create($validated);

        return redirect()->route('candidates.index')
            ->with('success', 'Thêm ứng viên thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Candidate $candidate)
    {
        if (auth()->user()->role != 'admin' && auth()->user()->role != 'hr') {
            if ($candidate->department_id != auth()->user()->department_id) {
                return redirect()->route('candidates.index')
                    ->with('error', 'Không thể xem ứng viên này!');
            }
        }

        return view('admin.pages.candidates.show', compact('candidate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Candidate $candidate)
    {
        $departments = Department::all();
        return view('admin.pages.candidates.edit', compact('candidate', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Candidate $candidate)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'desired_position' => 'required|string|max:255',
            'outlet_department' => 'required|string|max:255',
            'employment_type' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ], [
            'full_name.required' => 'Họ tên không được để trống',
            'desired_position.required' => 'Vị trí mong muốn không được để trống',
            'outlet_department.required' => 'Phòng ban không được để trống',
            'employment_type.required' => 'Loại hình công việc không được để trống',
            'department_id.required' => 'Phòng ban không được để trống',
            'department_id.exists' => 'Phòng ban không tồn tại',
        ]);

        // Cập nhật thông tin HR nếu người chỉnh sửa là HR và trước đó chưa có
        if (auth()->user()->role == 'hr' && empty($candidate->hr_name)) {
            $validated['hr_name'] = auth()->user()->name;
            $validated['hr_date'] = now();
        }

        $candidate->update($validated);

        return redirect()->route('candidates.index')
            ->with('success', 'Cập nhật ứng viên thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Candidate $candidate)
    {
        try {
            $candidate->delete();
            return redirect()->route('candidates.index')
                ->with('success', 'Xóa ứng viên thành công!');
        } catch (\Exception $e) {
            return redirect()->route('candidates.index')
                ->with('error', 'Không thể xóa ứng viên này!');
        }
    }
}
