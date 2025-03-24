<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
        }

        .header {
            background-color: #3490dc;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .content {
            padding: 20px;
            border: 1px solid #ddd;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .info-table th,
        .info-table td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .info-table th {
            background-color: #f8f9fa;
            text-align: left;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Thông Báo Lịch Phỏng Vấn</h2>
    </div>

    <div class="content">
        <p>Kính gửi
            {{ $interviewer ? $interviewer->name : ($role == 'hr' ? 'HR' : ($role == 'lm' ? 'Line Manager' : 'Final Interviewer')) }},
        </p>

        <p>Bạn được phân công phỏng vấn ứng viên sau:</p>

        <table class="info-table">
            <tr>
                <th>Họ tên ứng viên:</th>
                <td>{{ $candidate->full_name }}</td>
            </tr>
            <tr>
                <th>Vị trí ứng tuyển:</th>
                <td>{{ $candidate->desired_position }}</td>
            </tr>
            <tr>
                <th>Phòng ban:</th>
                <td>{{ $candidate->outlet_department }}</td>
            </tr>
            <tr>
                <th>Ngày phỏng vấn:</th>
                <td><strong>{{ $interviewDate->format('d/m/Y') }}</strong></td>
            </tr>
        </table>

        @if ($candidate->cv)
            <p>CV của ứng viên được đính kèm hoặc bạn có thể xem trực tiếp tại <a
                    href="{{ asset('storage/' . $candidate->cv) }}" target="_blank">link này</a>.</p>
        @endif

        <p>Vui lòng chuẩn bị và tiến hành phỏng vấn đúng thời gian. Sau khi hoàn thành, hãy đăng nhập vào hệ thống để
            cập nhật kết quả đánh giá.</p>

        <p>Trân trọng,<br>
            Ban Tuyển Dụng</p>
    </div>

    <div class="footer">
        <p>Đây là email tự động, vui lòng không trả lời email này.</p>
    </div>
</body>

</html>
