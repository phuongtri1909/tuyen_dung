<?php

namespace App\Mail;

use App\Models\Candidate;
use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InterviewNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $candidate;
    public $role;
    public $interviewDate;
    public $interviewer;
    protected $emailTemplate;

    /**
     * Create a new message instance.
     */
    public function __construct(Candidate $candidate, string $role)
    {
        $this->candidate = $candidate;
        $this->role = $role;

        // Xác định người phỏng vấn
        $interviewerField = "{$role}_interviewer_id";
        $this->interviewer = $candidate->$interviewerField ? $candidate->{$role . 'Interviewer'} : null;

        // Xác định ngày phỏng vấn
        $dateField = "{$role}_interview_date";
        $this->interviewDate = $candidate->$dateField;
        
        // Lấy mẫu email từ cơ sở dữ liệu
        $this->emailTemplate = EmailTemplate::getByRole($role);
    }

    /**
     * Thay thế các biến trong mẫu
     */
    protected function parseTemplate($content)
    {
        $replacements = [
            '[candidate_name]' => $this->candidate->full_name,
            '[candidate_position]' => $this->candidate->desired_position,
            '[department]' => $this->candidate->outlet_department,
            
            // Kiểm tra và xử lý ngày phỏng vấn
            '[interview_date]' => $this->formatDate($this->interviewDate),
            
            '[interviewer_name]' => $this->interviewer ? $this->interviewer->name : '',
            '[cv_link]' => $this->candidate->cv ? asset('storage/' . $this->candidate->cv) : '',
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }

    /**
     * Hàm hỗ trợ định dạng ngày an toàn
     */
    protected function formatDate($date)
    {
        if (empty($date)) {
            return '';
        }
        
        if (is_string($date)) {
            return \Carbon\Carbon::parse($date)->format('d/m/Y');
        }
        
        return $date->format('d/m/Y');
    }

    /**
     * Build the message.
     */
    public function build()
    {
        // Thiết lập tiêu đề email từ template hoặc mặc định
        $subject = "Thông báo lịch phỏng vấn ứng viên {$this->candidate->full_name}";
        
        if ($this->emailTemplate) {
            $subject = $this->parseTemplate($this->emailTemplate->subject);
        }

        $mail = $this->subject($subject);
        
        if ($this->emailTemplate) {
            // Nếu có mẫu email, dùng nội dung từ database
            $mail->view('emails.template', [
                'content' => $this->parseTemplate($this->emailTemplate->content)
            ]);
        } else {
            // Nếu không có mẫu, dùng mặc định
            $mail->view('emails.interview-notification', [
                'candidate' => $this->candidate,
                'role' => $this->role,
                'interviewer' => $this->interviewer,
                'interviewDate' => $this->interviewDate
            ]);
        }
        
        return $mail;
    }
}