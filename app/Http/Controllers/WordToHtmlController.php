<?php

namespace App\Http\Controllers;

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\WordData;

class WordToHtmlController extends Controller
{
    public function showWordAsHtml()
    {
        // Create temp directory if it doesn't exist
        $tempDir = storage_path('app/temp');
        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        // Thiết lập thư mục temp
        Settings::setTempDir($tempDir);

        try {
            // Đường dẫn đến file Word mẫu
            $wordFile = public_path('assets/file/my_template.docx');

            // Check if Word file exists
            if (!File::exists($wordFile)) {
                return "Error: Word template file not found at: {$wordFile}";
            }

            // Trả về view với đường dẫn đến file Word
            return view('admin.pages.intervie', ['wordFilePath' => asset('assets/file/my_template.docx')]);
        } catch (\Exception $e) {
            return redirect()->route('candidates.index')
                ->with('error', 'Không thể xem trang này!');
        }
    }

    public function saveWordData(Request $request)
    {
        // Validate dữ liệu
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'desired_position' => 'required|string|max:255',
            'outlet_department' => 'required|string|max:255',

            'employment_type' => 'nullable|string|in:fulltime,casual',

            'hr_name' => 'nullable|string|max:255',
            'hr_date' => 'nullable|string|max:255',
            'lm_name' => 'nullable|string|max:255',
            'lm_date' => 'nullable|string|max:255',
            'final_name' => 'nullable|string|max:255',
            'final_date' => 'nullable|string|max:255',

            // Yes/No questions
            'can_work_holidays' => 'nullable|boolean',
            'can_work_different_shifts' => 'nullable|boolean',
            'can_work_split_shifts' => 'nullable|boolean',
            'can_work_overtime' => 'nullable|boolean',
            'can_work_late_shift' => 'nullable|boolean',

            // Other fields
            'notice_days' => 'nullable|string|max:255',
            'available_date' => 'nullable|string|max:255',
            'min_salary' => 'nullable|string|max:255',

            // Các trường đánh giá
            'hr_rating_appearance' => 'nullable|integer|min:1|max:10',
            'lm_rating_appearance' => 'nullable|integer|min:1|max:10',
            'final_rating_appearance' => 'nullable|integer|min:1|max:10',

            'hr_rating_english' => 'nullable|integer|min:1|max:10',
            'lm_rating_english' => 'nullable|integer|min:1|max:10',
            'final_rating_english' => 'nullable|integer|min:1|max:10',

            'hr_rating_chinese' => 'nullable|integer|min:1|max:10',
            'lm_rating_chinese' => 'nullable|integer|min:1|max:10',
            'final_rating_chinese' => 'nullable|integer|min:1|max:10',

            'hr_rating_japanese' => 'nullable|integer|min:1|max:10',
            'lm_rating_japanese' => 'nullable|integer|min:1|max:10',
            'final_rating_japanese' => 'nullable|integer|min:1|max:10',

            'hr_rating_computer' => 'nullable|integer|min:1|max:10',
            'lm_rating_computer' => 'nullable|integer|min:1|max:10',
            'final_rating_computer' => 'nullable|integer|min:1|max:10',

            'hr_rating_behavior' => 'nullable|integer|min:1|max:10',
            'lm_rating_behavior' => 'nullable|integer|min:1|max:10',
            'final_rating_behavior' => 'nullable|integer|min:1|max:10',

            'hr_rating_characteristics' => 'nullable|integer|min:1|max:10',
            'lm_rating_characteristics' => 'nullable|integer|min:1|max:10',
            'final_rating_characteristics' => 'nullable|integer|min:1|max:10',

            'hr_rating_communication' => 'nullable|integer|min:1|max:10',
            'lm_rating_communication' => 'nullable|integer|min:1|max:10',
            'final_rating_communication' => 'nullable|integer|min:1|max:10',

            'hr_rating_motivation' => 'nullable|integer|min:1|max:10',
            'lm_rating_motivation' => 'nullable|integer|min:1|max:10',
            'final_rating_motivation' => 'nullable|integer|min:1|max:10',

            'hr_rating_experience' => 'nullable|integer|min:1|max:10',
            'lm_rating_experience' => 'nullable|integer|min:1|max:10',
            'final_rating_experience' => 'nullable|integer|min:1|max:10',

            'hr_rating_customer' => 'nullable|integer|min:1|max:10',
            'lm_rating_customer' => 'nullable|integer|min:1|max:10',
            'final_rating_customer' => 'nullable|integer|min:1|max:10',

            'hr_rating_flexibility' => 'nullable|integer|min:1|max:10',
            'lm_rating_flexibility' => 'nullable|integer|min:1|max:10',
            'final_rating_flexibility' => 'nullable|integer|min:1|max:10',

            'hr_rating_teamwork' => 'nullable|integer|min:1|max:10',
            'lm_rating_teamwork' => 'nullable|integer|min:1|max:10',
            'final_rating_teamwork' => 'nullable|integer|min:1|max:10',
        ]);

        // Lưu dữ liệu vào DB
        WordData::create($validated);

        // Redirect với thông báo thành công
        return redirect()->back()->with('success', 'Dữ liệu đã được lưu thành công!');
    }

    // Phương thức để xem và xuất file Word (nếu cần)
    public function generateWordFromSavedData($id)
    {
        // Tìm dữ liệu
        $data = WordData::findOrFail($id);

        // Đường dẫn đến template
        $templatePath = public_path('assets/file/my_template.docx');

        // Tạo template processor
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        // Điền dữ liệu vào template
        $templateProcessor->setValue('full_name', $data->full_name);
        $templateProcessor->setValue('desired_position', $data->desired_position);
        $templateProcessor->setValue('outlet_department', $data->outlet_department);

        $templateProcessor->setValue('employment_fulltime_check', '☑');
        $templateProcessor->setValue('employment_casual_check', '☐');


        $templateProcessor->setValue('hr_name', $data->hr_name ?? '');
        $templateProcessor->setValue('hr_date', $data->hr_date ?? '');
        $templateProcessor->setValue('lm_name', $data->lm_name ?? '');
        $templateProcessor->setValue('lm_date', $data->lm_date ?? '');
        $templateProcessor->setValue('final_name', $data->final_name ?? '');
        $templateProcessor->setValue('final_date', $data->final_date ?? '');

        // Xử lý các câu hỏi Yes/No
        $checkYes = '☑';
        $checkNo = '☐';

        // Question 1
        $templateProcessor->setValue('can_work_holidays_yes', $data->can_work_holidays ? $checkYes : $checkNo);
        $templateProcessor->setValue('can_work_holidays_no', !$data->can_work_holidays ? $checkYes : $checkNo);

        // Question 2
        $templateProcessor->setValue('can_work_different_shifts_yes', $data->can_work_different_shifts ? $checkYes : $checkNo);
        $templateProcessor->setValue('can_work_different_shifts_no', !$data->can_work_different_shifts ? $checkYes : $checkNo);

        // Question 3
        $templateProcessor->setValue('can_work_split_shifts_yes', $data->can_work_split_shifts ? $checkYes : $checkNo);
        $templateProcessor->setValue('can_work_split_shifts_no', !$data->can_work_split_shifts ? $checkYes : $checkNo);

        // Question 4
        $templateProcessor->setValue('can_work_overtime_yes', $data->can_work_overtime ? $checkYes : $checkNo);
        $templateProcessor->setValue('can_work_overtime_no', !$data->can_work_overtime ? $checkYes : $checkNo);

        // Question 5
        $templateProcessor->setValue('can_work_late_shift_yes', $data->can_work_late_shift ? $checkYes : $checkNo);
        $templateProcessor->setValue('can_work_late_shift_no', !$data->can_work_late_shift ? $checkYes : $checkNo);

        // Các trường khác
        $templateProcessor->setValue('notice_days', $data->notice_days ?? '350');
        $templateProcessor->setValue('available_date', $data->available_date ?? '19/09/2003');
        $templateProcessor->setValue('min_salary', $data->min_salary ?? '1200000');
        // Điền các trường khác

        // Điền các điểm đánh giá
        // 1. Appearance
        $templateProcessor->setValue('hr_rating_appearance', $data->hr_rating_appearance ?? '');
        $templateProcessor->setValue('lm_rating_appearance', $data->lm_rating_appearance ?? '');
        $templateProcessor->setValue('final_rating_appearance', $data->final_rating_appearance ?? '');

        // 2. English
        $templateProcessor->setValue('hr_rating_english', $data->hr_rating_english ?? '');
        $templateProcessor->setValue('lm_rating_english', $data->lm_rating_english ?? '');
        $templateProcessor->setValue('final_rating_english', $data->final_rating_english ?? '');

        // 3. Chinese
        $templateProcessor->setValue('hr_rating_chinese', $data->hr_rating_chinese ?? '');
        $templateProcessor->setValue('lm_rating_chinese', $data->lm_rating_chinese ?? '');
        $templateProcessor->setValue('final_rating_chinese', $data->final_rating_chinese ?? '');

        // 4. Japanese
        $templateProcessor->setValue('hr_rating_japanese', $data->hr_rating_japanese ?? '');
        $templateProcessor->setValue('lm_rating_japanese', $data->lm_rating_japanese ?? '');
        $templateProcessor->setValue('final_rating_japanese', $data->final_rating_japanese ?? '');

        // 5. Computer skills
        $templateProcessor->setValue('hr_rating_computer', $data->hr_rating_computer ?? '');
        $templateProcessor->setValue('lm_rating_computer', $data->lm_rating_computer ?? '');
        $templateProcessor->setValue('final_rating_computer', $data->final_rating_computer ?? '');

        // 6. Behavior during interview
        $templateProcessor->setValue('hr_rating_behavior', $data->hr_rating_behavior ?? '');
        $templateProcessor->setValue('lm_rating_behavior', $data->lm_rating_behavior ?? '');
        $templateProcessor->setValue('final_rating_behavior', $data->final_rating_behavior ?? '');

        // 7. Characteristics
        $templateProcessor->setValue('hr_rating_characteristics', $data->hr_rating_characteristics ?? '');
        $templateProcessor->setValue('lm_rating_characteristics', $data->lm_rating_characteristics ?? '');
        $templateProcessor->setValue('final_rating_characteristics', $data->final_rating_characteristics ?? '');

        // 8. Communication skills
        $templateProcessor->setValue('hr_rating_communication', $data->hr_rating_communication ?? '');
        $templateProcessor->setValue('lm_rating_communication', $data->lm_rating_communication ?? '');
        $templateProcessor->setValue('final_rating_communication', $data->final_rating_communication ?? '');

        // 9. Motivation
        $templateProcessor->setValue('hr_rating_motivation', $data->hr_rating_motivation ?? '');
        $templateProcessor->setValue('lm_rating_motivation', $data->lm_rating_motivation ?? '');
        $templateProcessor->setValue('final_rating_motivation', $data->final_rating_motivation ?? '');

        // 10. Experience from previous jobs
        $templateProcessor->setValue('hr_rating_experience', $data->hr_rating_experience ?? '');
        $templateProcessor->setValue('lm_rating_experience', $data->lm_rating_experience ?? '');
        $templateProcessor->setValue('final_rating_experience', $data->final_rating_experience ?? '');

        // 11. Customer handling experience
        $templateProcessor->setValue('hr_rating_customer', $data->hr_rating_customer ?? '');
        $templateProcessor->setValue('lm_rating_customer', $data->lm_rating_customer ?? '');
        $templateProcessor->setValue('final_rating_customer', $data->final_rating_customer ?? '');

        // 12. Flexibility
        $templateProcessor->setValue('hr_rating_flexibility', $data->hr_rating_flexibility ?? '');
        $templateProcessor->setValue('lm_rating_flexibility', $data->lm_rating_flexibility ?? '');
        $templateProcessor->setValue('final_rating_flexibility', $data->final_rating_flexibility ?? '');

        // 13. Teamwork
        $templateProcessor->setValue('hr_rating_teamwork', $data->hr_rating_teamwork ?? '');
        $templateProcessor->setValue('lm_rating_teamwork', $data->lm_rating_teamwork ?? '');
        $templateProcessor->setValue('final_rating_teamwork', $data->final_rating_teamwork ?? '');

        // Tạo file output
        $outputPath = public_path('assets/file/generated/document_' . time() . '.docx');
        $templateProcessor->saveAs($outputPath);

        // Trả về file để download
        return response()->download($outputPath)->deleteFileAfterSend(true);
    }

    public function listWordData()
    {
        $data = WordData::orderBy('created_at', 'desc')->get();
        return view('word_data_list', ['data' => $data]);
    }
}
