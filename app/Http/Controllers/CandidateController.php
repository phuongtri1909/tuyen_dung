<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Department;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use App\Models\RecommendedAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{

    public function generateWordFromSavedData($id)
    {
        // Tìm dữ liệu ứng viên và các đánh giá liên quan
        $candidate = Candidate::with(['evaluations', 'recommendations'])->findOrFail($id);

        // Đường dẫn đến template
        $templatePath = public_path('assets/file/my_template2.docx');
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        // Thông tin cơ bản của ứng viên - sử dụng biến ngắn gọn
        $templateProcessor->setValue('name', $candidate->full_name ?? '');
        $templateProcessor->setValue('pos', $candidate->desired_position ?? '');
        $templateProcessor->setValue('dept', $candidate->outlet_department ?? '');

        // Loại hình công việc
        $employmentType = $candidate->employment_type ?? '';
        if (!empty($employmentType)) {
            $isFullTime = $employmentType == 'full-time';
            $templateProcessor->setValue('ft', $isFullTime ? '☑' : '☐');
            $templateProcessor->setValue('cl', !$isFullTime ? '☑' : '☐');
        } else {
            // Trường hợp không có dữ liệu, để trống
            $templateProcessor->setValue('ft', '');
            $templateProcessor->setValue('cl', '');
        }

        // Thông tin người phỏng vấn
        $templateProcessor->setValue('hr1', $candidate->hr_name ?? '');
        $templateProcessor->setValue('hr2', $candidate->hr_date ?? '');
        $templateProcessor->setValue('lm1', $candidate->lm_name ?? '');
        $templateProcessor->setValue('lm2', $candidate->lm_date ?? '');
        $templateProcessor->setValue('f1', $candidate->final_name ?? '');
        $templateProcessor->setValue('f2', $candidate->final_date ?? '');

        // Xử lý các câu hỏi Yes/No
        $checkYes = 'Y̲e̲s̲';  // Yes có gạch chân
        $checkNo = 'N̲o̲';    // No có gạch chân
        $plainYes = 'Yes';         // Yes không gạch chân
        $plainNo = 'No';           // No không gạch chân

        // Mapping biến cũ sang biến mới ngắn gọn hơn
        $booleanFields = [
            'can_work_holidays' => 'q1',
            'can_work_different_shifts' => 'q2',
            'can_work_split_shifts' => 'q3',
            'can_work_overtime' => 'q4',
            'can_work_late_shift' => 'q5'
        ];

        foreach ($booleanFields as $dbField => $templateField) {
            if (isset($candidate->$dbField) && $candidate->$dbField !== null) {
                $value = (bool)$candidate->$dbField;
                $templateProcessor->setValue("{$templateField}y", $value ? $checkYes : $plainYes);
                $templateProcessor->setValue("{$templateField}n", !$value ? $checkNo : $plainNo);
            } else {
                $templateProcessor->setValue("{$templateField}y", $plainYes);
                $templateProcessor->setValue("{$templateField}n", $plainNo);
            }
        }

        // Các trường thông tin khác
        $templateProcessor->setValue('nd', $candidate->notice_days ?? '');
        $templateProcessor->setValue('ad', $candidate->available_date ?? '');
        $templateProcessor->setValue('ms', $candidate->min_salary ?? '');

        // Xử lý dữ liệu đánh giá
        $evaluations = [];
        $totalScores = [
            'hr' => 0,
            'lm' => 0,
            'final' => 0
        ];

        // Tổ chức dữ liệu đánh giá theo cấu trúc role => criteria => rating
        foreach ($candidate->evaluations as $evaluation) {
            $evaluations[$evaluation->role][$evaluation->criteria] = $evaluation->rating;
            $totalScores[$evaluation->role] += $evaluation->rating;
        }

        // Danh sách tiêu chí đánh giá - Sử dụng số để đánh dấu
        $criteriaList = [
            'appearance' => 'c1',
            'english' => 'c2',
            'chinese' => 'c3',
            'japanese' => 'c4',
            'computer' => 'c5',
            'behavior' => 'c6',
            'characteristics' => 'c7',
            'communication' => 'c8',
            'motivation' => 'c9',
            'experience' => 'c10',
            'customer' => 'c11',
            'flexibility' => 'c12',
            'teamwork' => 'c13'
        ];

        // Thiết lập giá trị đánh giá cho template - Map với shortcode
        $roleMap = ['hr' => 'h', 'lm' => 'l', 'final' => 'f'];

        foreach ($roleMap as $role => $shortRole) {
            foreach ($criteriaList as $criteria => $shortCriteria) {
                $rating = $evaluations[$role][$criteria] ?? '';
                $templateProcessor->setValue("{$shortRole}{$shortCriteria}", $rating);
            }
        }

        // Thiết lập tổng điểm cho mỗi vai trò
        $templateProcessor->setValue('hts', $totalScores['hr']);
        $templateProcessor->setValue('lts', $totalScores['lm']);
        $templateProcessor->setValue('fts', $totalScores['final']);

        // Tính điểm trung bình
        $validScores = collect($totalScores)->filter(function ($score) {
            return $score > 0;
        });

        $averageScore = $validScores->count() > 0 ? round($validScores->sum() / $validScores->count()) : 0;
        $templateProcessor->setValue('avgs', $averageScore);

        // Xác định đánh giá tổng thể
        $checkMark = '☑';
        $uncheck = '☐';

        if ($averageScore >= 1 && $averageScore <= 52) {
            $templateProcessor->setValue('us', $checkMark);
            $templateProcessor->setValue('gs', $uncheck);
            $templateProcessor->setValue('es', $uncheck);
        } elseif ($averageScore >= 53 && $averageScore <= 104) {
            $templateProcessor->setValue('us', $uncheck);
            $templateProcessor->setValue('gs', $checkMark);
            $templateProcessor->setValue('es', $uncheck);
        } elseif ($averageScore >= 105) {
            $templateProcessor->setValue('us', $uncheck);
            $templateProcessor->setValue('gs', $uncheck);
            $templateProcessor->setValue('es', $checkMark);
        } else {
            $templateProcessor->setValue('us', $uncheck);
            $templateProcessor->setValue('gs', $uncheck);
            $templateProcessor->setValue('es', $uncheck);
        }

        // Cấu trúc dữ liệu đề xuất
        $recommendations = [];
        foreach ($candidate->recommendations as $recommendation) {
            $recommendations[$recommendation->role] = [
                'action' => $recommendation->action,
                'propose_next_step' => $recommendation->propose_next_step,
                'other_position_detail' => $recommendation->other_position_detail
            ];
        }

        // Thiết lập dữ liệu đề xuất cho template - Sử dụng mã ngắn
        foreach ($roleMap as $role => $shortRole) {
            // Text area action
            $templateProcessor->setValue("{$shortRole}act", $recommendations[$role]['action'] ?? '');
            $templateProcessor->setValue("{$shortRole}_opd", $recommendations[$role]['other_position_detail'] ?? '');

            // Các tùy chọn đề xuất với mã ngắn
            $proposeOptions = [
                'highly_recommend' => 'hr',
                'recommend' => 'rc',
                'do_not_recommend' => 'dr',
                'hold_consider' => 'hc',
                'other_position' => 'op'
            ];

            $selectedOption = $recommendations[$role]['propose_next_step'] ?? '';

            foreach ($proposeOptions as $option => $shortOption) {
                $isSelected = ($selectedOption == $option);
                // Ví dụ: h_hr, h_rc, l_hr, l_rc, f_hr, f_rc,...
                $templateProcessor->setValue("{$shortRole}_{$shortOption}", $isSelected ? $checkMark : $uncheck);
            }
        }

        // Phản hồi tham khảo
        $templateProcessor->setValue('rf', $candidate->reference_feedback ?? '');


        // Tạo file output
        $outputPath = public_path('assets/file/generated/ie_' . $candidate->full_name . '_' . time() . '.docx');
        $templateProcessor->saveAs($outputPath);

        // Trả về file để download
        return response()->download($outputPath)->deleteFileAfterSend(true);
    }

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
                'can_work_late_shift' => $request->can_work_late_shift,
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
                    $otherPositionDetailField = "{$role}_other_position_detail";

                    if (($request->has($actionField) && !empty($request->$actionField)) ||
                        ($request->has($recommendationField) && !empty($request->$recommendationField))
                    ) {
                        $proposeNextStep = $request->$recommendationField ?? '';

                        // Lưu thông tin other_position_detail nếu chọn other_position
                        $otherPositionDetail = null;
                        if ($proposeNextStep === 'other_position' && $request->has($otherPositionDetailField)) {
                            $otherPositionDetail = $request->$otherPositionDetailField;
                        }

                        RecommendedAction::create([
                            'candidate_id' => $candidate->id,
                            'role' => $role,
                            'action' => $request->$actionField ?? '',
                            'propose_next_step' => $proposeNextStep,
                            'other_position_detail' => $otherPositionDetail
                        ]);
                    }
                }
            }

            DB::commit();

            // Nếu là yêu cầu AJAX (lưu tự động), trả về JSON
            if ($request->ajax() || $request->has('auto_save')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đã lưu dữ liệu thành công',
                    'timestamp' => now()->format('H:i:s')
                ]);
            }

            // Ngược lại trả về redirect với thông báo
            return redirect()->route('candidates.show', $candidate->id)
                ->with('success', 'Đánh giá phỏng vấn đã được lưu thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Lỗi lưu đánh giá ứng viên: ' . $e->getMessage());

            // Nếu là yêu cầu AJAX (lưu tự động), trả về JSON
            if ($request->ajax() || $request->has('auto_save')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể lưu đánh giá: ' . $e->getMessage()
                ]);
            }

            // Ngược lại trả về redirect với thông báo lỗi
            return back()->with('error', 'Không thể lưu đánh giá: ' . $e->getMessage())->withInput();
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Candidate::query();

        // Xử lý tìm kiếm theo tên, vị trí, phòng ban
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('desired_position', 'like', "%{$search}%")
                    ->orWhere('outlet_department', 'like', "%{$search}%");
            });
        }

        // Xử lý tìm kiếm theo khoảng thời gian (created_at)
        if ($request->has('from_date') && !empty($request->from_date)) {
            $fromDate = date('Y-m-d 00:00:00', strtotime($request->from_date));
            $query->where('created_at', '>=', $fromDate);
        }

        if ($request->has('to_date') && !empty($request->to_date)) {
            $toDate = date('Y-m-d 23:59:59', strtotime($request->to_date));
            $query->where('created_at', '<=', $toDate);
        }

        // Phân quyền: nếu không phải admin và hr thì chỉ xem được ứng viên cùng phòng ban
        if (auth()->user()->role != 'admin' && auth()->user()->role != 'hr') {
            $query->where('department_id', auth()->user()->department_id);
        }

        $candidates = $query->latest()->paginate(10);

        // Đảm bảo duy trì các tham số tìm kiếm khi phân trang
        $candidates->appends($request->except('page'));

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
            'cv' => 'nullable|file|mimes:pdf'
        ], [
            'full_name.required' => 'Họ tên không được để trống',
            'desired_position.required' => 'Vị trí mong muốn không được để trống',
            'outlet_department.required' => 'Phòng ban không được để trống',
            'employment_type.required' => 'Loại hình công việc không được để trống',
            'employment_type.in' => 'Loại hình công việc không hợp lệ',
            'department_id.required' => 'Phòng ban không được để trống',
            'department_id.exists' => 'Phòng ban không tồn tại',
            'cv.mimes' => 'CV phải là file PDF',
        ]);

        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('candidate-cvs', 'public');
            $validated['cv'] = $cvPath;
        }

        Candidate::create($validated);

        return redirect()->route('candidates.index')
            ->with('success', 'Thêm ứng viên thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Candidate $candidate)
    {
        $candidate->load(['evaluations', 'recommendations', 'department']);
        
        // Tổ chức dữ liệu đánh giá theo cấu trúc role => criteria => rating
        $evaluations = [];
        foreach ($candidate->evaluations as $evaluation) {
            $evaluations[$evaluation->role][$evaluation->criteria] = $evaluation->rating;
        }
        
        // Tổ chức dữ liệu đề xuất theo role
        $recommendations = [];
        foreach ($candidate->recommendations as $recommendation) {
            $recommendations[$recommendation->role] = [
                'action' => $recommendation->action,
                'propose_next_step' => $recommendation->propose_next_step,
                'other_position_detail' => $recommendation->other_position_detail
            ];
        }
        
        return view('admin.pages.candidates.show', compact('candidate', 'evaluations', 'recommendations'));
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
            'cv' => 'nullable|file|mimes:pdf'
        ], [
            'full_name.required' => 'Họ tên không được để trống',
            'desired_position.required' => 'Vị trí mong muốn không được để trống',
            'outlet_department.required' => 'Phòng ban không được để trống',
            'employment_type.required' => 'Loại hình công việc không được để trống',
            'department_id.required' => 'Phòng ban không được để trống',
            'department_id.exists' => 'Phòng ban không tồn tại',
            'cv.mimes' => 'CV phải là file PDF',
        ]);

        // Xử lý upload CV mới
        if ($request->hasFile('cv')) {
            // Xóa CV cũ nếu có
            if ($candidate->cv) {
                Storage::disk('public')->delete($candidate->cv);
            }

            // Lưu CV mới
            $cvPath = $request->file('cv')->store('candidate-cvs', 'public');
            $validated['cv'] = $cvPath;
        }

        // Xử lý xóa CV
        if ($request->has('remove_cv') && $request->remove_cv && !$request->hasFile('cv')) {
            if ($candidate->cv) {
                Storage::disk('public')->delete($candidate->cv);
            }
            $validated['cv'] = null;
        }

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

            if ($candidate->cv) {
                Storage::disk('public')->delete($candidate->cv);
            }

            $candidate->delete();
            return redirect()->route('candidates.index')
                ->with('success', 'Xóa ứng viên thành công!');
        } catch (\Exception $e) {
            return redirect()->route('candidates.index')
                ->with('error', 'Không thể xóa ứng viên này!');
        }
    }
}
