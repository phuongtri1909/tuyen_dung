@extends('admin.layouts.app')

@push('styles-admin')
    <style>
        .interview-info-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .evaluation-table th,
        .evaluation-table td {
            padding: 12px 15px;
            vertical-align: middle;
        }

        .score-badge {
            font-size: 14px;
            padding: 6px 10px;
        }

        .rating-value {
            font-weight: bold;
            font-size: 16px;
            display: inline-block;
            width: 35px;
            height: 35px;
            line-height: 35px;
            text-align: center;
            border-radius: 50%;
            background-color: #f8f9fa;
        }

        .section-divider {
            height: 1px;
            background-color: rgba(0, 0, 0, 0.1);
            margin: 30px 0;
        }

        .cv-preview-container {
            height: 500px;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #dee2e6;
        }

        .recommendation-box {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .total-score {
            font-size: 18px;
            font-weight: bold;
        }

        .average-score-box {
            border: 2px solid #dee2e6;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
        }

        .average-score {
            font-size: 32px;
            font-weight: bold;
        }
    </style>
@endpush

@section('content-auth')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0 px-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Thông tin ứng viên: {{ $candidate->full_name }}</h5>
                        <div>
                            <a href="{{ route('generate.word', $candidate->id) }}" class="btn btn-info btn-sm"
                                title="Xuất Word">
                                <i class="fa-solid fa-file-word me-1"></i> Xuất Word
                            </a>

                            @if (auth()->user()->role != 'admin')
                                <a target="_blank" href="{{ route('candidates.interview', $candidate->id) }}"
                                    class="btn btn-primary btn-sm" title="Phỏng vấn">
                                    <i class="fa-solid fa-users-viewfinder me-1"></i> Phỏng vấn
                                </a>
                            @endif

                            @if (auth()->user()->role == 'admin')
                                <a href="{{ route('candidates.edit', $candidate->id) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-edit me-1"></i> Chỉnh sửa
                                </a>
                            @endif

                            <a href="{{ route('candidates.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-4 p-3">
                    <!-- Thông tin cá nhân -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card interview-info-card">
                                <div class="card-body">
                                    <h6 class="text-uppercase text-primary font-weight-bolder mb-3">Thông tin cơ bản</h6>
                                    <div class="table-responsive">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <td width="40%"><strong>Họ tên</strong></td>
                                                    <td>{{ $candidate->full_name }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Vị trí mong muốn</strong></td>
                                                    <td>{{ $candidate->desired_position }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Phòng ban ứng tuyển</strong></td>
                                                    <td>{{ $candidate->outlet_department }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Loại hình công việc</strong></td>
                                                    <td>
                                                        <span
                                                            class="badge bg-{{ $candidate->employment_type == 'full-time' ? 'primary' : 'info' }}">
                                                            {{ $candidate->employment_type == 'full-time' ? 'Toàn thời gian' : 'Bán thời gian' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Phòng ban quản lý</strong></td>
                                                    <td>{{ $candidate->department->name ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>CV</strong></td>
                                                    <td>
                                                        @if ($candidate->cv)
                                                            <div>
                                                                <a href="{{ asset('storage/' . $candidate->cv) }}"
                                                                    target="_blank" class="btn btn-sm btn-primary">
                                                                    <i class="fas fa-file-pdf"></i> Xem
                                                                </a>
                                                                <a href="{{ asset('storage/' . $candidate->cv) }}" download
                                                                    class="btn btn-sm btn-info">
                                                                    <i class="fas fa-download"></i> Tải xuống
                                                                </a>
                                                            </div>
                                                        @else
                                                            <span class="badge bg-warning">Chưa có CV</span>
                                                        @endif
                                                    </td>
                                                </tr>


                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card interview-info-card">
                                <div class="card-body">
                                    <h6 class="text-uppercase text-primary font-weight-bolder mb-3">Người phỏng vấn</h6>
                                    <div class="table-responsive">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <td width="15%"><strong>HR</strong></td>
                                                    <td>
                                                        @if ($candidate->hr_name)
                                                            {{ $candidate->hr_name }}
                                                            <div class="text-xs text-muted">
                                                                {{ date('d/m/Y', strtotime($candidate->hr_date)) }}
                                                            </div>
                                                        @else
                                                            <span class="badge bg-secondary">Chưa đánh giá</span>
                                                        @endif

                                                        @if ($candidate->hr_interview_date)
                                                            <div class="mt-1">
                                                                <span class="badge bg-primary">Ngày hẹn:
                                                                    {{ date('d/m/Y', strtotime($candidate->hr_interview_date)) }}</span>
                                                                @if ($candidate->hrInterviewer)
                                                                    <span class="badge bg-info">
                                                                        <i class="fas fa-user"></i>
                                                                        {{ $candidate->hrInterviewer->name }}
                                                                    </span>
                                                                @endif

                                                                @if (!$candidate->hr_notified && auth()->user()->role == 'admin')
                                                                    <a href="{{ route('candidates.send-notification', ['candidate' => $candidate->id, 'role' => 'hr']) }}"
                                                                        class="btn btn-sm btn-outline-info ms-1 mb-0"
                                                                        title="Gửi email thông báo">
                                                                        <i class="fas fa-envelope"></i> Thông báo
                                                                    </a>
                                                                @elseif($candidate->hr_notified)
                                                                    <span class="badge bg-success">Đã thông báo</span>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>LM</strong></td>
                                                    <td>
                                                        @if ($candidate->lm_name)
                                                            {{ $candidate->lm_name }}
                                                            <div class="text-xs text-muted">
                                                                {{ date('d/m/Y', strtotime($candidate->lm_date)) }}
                                                            </div>
                                                        @else
                                                            <span class="badge bg-secondary">Chưa đánh giá</span>
                                                        @endif

                                                        @if ($candidate->lm_interview_date)
                                                            <div class="mt-1">
                                                                <span class="badge bg-success">Ngày hẹn:
                                                                    {{ date('d/m/Y', strtotime($candidate->lm_interview_date)) }}</span>
                                                                @if ($candidate->lmInterviewer)
                                                                    <span class="badge bg-info">
                                                                        <i class="fas fa-user"></i>
                                                                        {{ $candidate->lmInterviewer->name }}
                                                                    </span>
                                                                @endif

                                                                @if (!$candidate->lm_notified && auth()->user()->role == 'admin')
                                                                    <a href="{{ route('candidates.send-notification', ['candidate' => $candidate->id, 'role' => 'lm']) }}"
                                                                        class="btn btn-sm btn-outline-info ms-1 mb-0"
                                                                        title="Gửi email thông báo">
                                                                        <i class="fas fa-envelope"></i> Thông báo
                                                                    </a>
                                                                @elseif($candidate->lm_notified)
                                                                    <span class="badge bg-success">Đã thông báo</span>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Final</strong></td>
                                                    <td>
                                                        @if ($candidate->final_name)
                                                            {{ $candidate->final_name }}
                                                            <div class="text-xs text-muted">
                                                                {{ date('d/m/Y', strtotime($candidate->final_date)) }}
                                                            </div>
                                                        @else
                                                            <span class="badge bg-secondary">Chưa đánh giá</span>
                                                        @endif

                                                        @if ($candidate->final_interview_date)
                                                            <div class="mt-1">
                                                                <span class="badge bg-warning">Ngày hẹn:
                                                                    {{ date('d/m/Y', strtotime($candidate->final_interview_date)) }}</span>
                                                                @if ($candidate->finalInterviewer)
                                                                    <span class="badge bg-info">
                                                                        <i class="fas fa-user"></i>
                                                                        {{ $candidate->finalInterviewer->name }}
                                                                    </span>
                                                                @endif

                                                                @if (!$candidate->final_notified && auth()->user()->role == 'admin')
                                                                    <a href="{{ route('candidates.send-notification', ['candidate' => $candidate->id, 'role' => 'final']) }}"
                                                                        class="btn btn-sm btn-outline-info ms-1 mb-0"
                                                                        title="Gửi email thông báo">
                                                                        <i class="fas fa-envelope"></i> Thông báo
                                                                    </a>
                                                                @elseif($candidate->final_notified)
                                                                    <span class="badge bg-success">Đã thông báo</span>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Điểm đánh giá trung bình -->
                            @php
                                // Tính điểm trung bình từ các đánh giá
                                $totalScores = [
                                    'hr' => $candidate->evaluations->where('role', 'hr')->sum('rating'),
                                    'lm' => $candidate->evaluations->where('role', 'lm')->sum('rating'),
                                    'final' => $candidate->evaluations->where('role', 'final')->sum('rating'),
                                ];

                                $validScores = collect($totalScores)->filter(function ($score) {
                                    return $score > 0;
                                });

                                $averageScore =
                                    $validScores->count() > 0 ? round($validScores->sum() / $validScores->count()) : 0;
                            @endphp

                            <div class="average-score-box mt-3">
                                <h6 class="text-uppercase text-primary font-weight-bolder mb-2">Điểm trung bình</h6>
                                <div class="average-score">{{ $averageScore }}</div>

                                @if ($averageScore > 0)
                                    @if ($averageScore >= 1 && $averageScore <= 52)
                                        <div class="mt-2"><span class="badge bg-danger">Không đạt (1-52)</span></div>
                                    @elseif($averageScore >= 53 && $averageScore <= 104)
                                        <div class="mt-2"><span class="badge bg-success">Đạt (53-104)</span></div>
                                    @elseif($averageScore >= 105)
                                        <div class="mt-2"><span class="badge bg-primary">Xuất sắc (105+)</span></div>
                                    @endif
                                @else
                                    <div class="mt-2"><span class="badge bg-secondary">Chưa đánh giá</span></div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Khả năng làm việc -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card interview-info-card">
                                <div class="card-body">
                                    <h6 class="text-uppercase text-primary font-weight-bolder mb-3">Yêu cầu cơ bản cho vị
                                        trí</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item d-flex justify-content-between px-0">
                                                    <span>1. Có thể làm việc vào ngày lễ và cuối tuần</span>
                                                    <span
                                                        class="badge bg-{{ $candidate->can_work_holidays ? 'success' : 'danger' }}">
                                                        {{ $candidate->can_work_holidays ? 'Có' : 'Không' }}
                                                    </span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between px-0">
                                                    <span>2. Có thể làm các ca khác nhau</span>
                                                    <span
                                                        class="badge bg-{{ $candidate->can_work_different_shifts ? 'success' : 'danger' }}">
                                                        {{ $candidate->can_work_different_shifts ? 'Có' : 'Không' }}
                                                    </span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between px-0">
                                                    <span>3. Có thể làm ca gãy</span>
                                                    <span
                                                        class="badge bg-{{ $candidate->can_work_split_shifts ? 'success' : 'danger' }}">
                                                        {{ $candidate->can_work_split_shifts ? 'Có' : 'Không' }}
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item d-flex justify-content-between px-0">
                                                    <span>4. Có sẵn sàng làm thêm giờ</span>
                                                    <span
                                                        class="badge bg-{{ $candidate->can_work_overtime ? 'success' : 'danger' }}">
                                                        {{ $candidate->can_work_overtime ? 'Có' : 'Không' }}
                                                    </span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between px-0">
                                                    <span>5. Có thể làm ca đêm</span>
                                                    <span
                                                        class="badge bg-{{ $candidate->can_work_late_shift ? 'success' : 'danger' }}">
                                                        {{ $candidate->can_work_late_shift ? 'Có' : 'Không' }}
                                                    </span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between px-0">
                                                    <span>6. Số ngày thông báo trước</span>
                                                    <span class="fw-bold">{{ $candidate->notice_days ?? 'N/A' }}
                                                        ngày</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="card bg-light">
                                                <div class="card-body py-2">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span>Ngày có thể bắt đầu</span>
                                                        <strong>{{ $candidate->available_date ? date('d/m/Y', strtotime($candidate->available_date)) : 'N/A' }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card bg-light">
                                                <div class="card-body py-2">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span>Mức lương mong muốn</span>
                                                        <strong>
                                                            @if (is_numeric($candidate->min_salary))
                                                                {{ number_format((float)$candidate->min_salary) }} VNĐ
                                                            @else
                                                                {{ $candidate->min_salary ?? 'Chưa xác định' }}
                                                            @endif
                                                        </strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bảng đánh giá chi tiết -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card interview-info-card">
                                <div class="card-body">
                                    <h6 class="text-uppercase text-primary font-weight-bolder mb-3">Đánh giá chi tiết
                                        (1-10)</h6>

                                    <div class="table-responsive">
                                        <table class="table evaluation-table">
                                            <thead>
                                                <tr class="bg-light">
                                                    <th width="40%">Tiêu chí đánh giá</th>
                                                    <th class="text-center">HR</th>
                                                    <th class="text-center">LM</th>
                                                    <th class="text-center">Final</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><strong>1. Appearance & Attire</strong><br><small
                                                            class="text-muted">Dung mạo & Trang phục</small></td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['hr']['appearance']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['hr']['appearance'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['lm']['appearance']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['lm']['appearance'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['final']['appearance']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['final']['appearance'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><strong>2. English</strong><br><small class="text-muted">Tiếng
                                                            Anh</small></td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['hr']['english']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['hr']['english'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['lm']['english']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['lm']['english'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['final']['english']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['final']['english'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><strong>3. Chinese</strong><br><small class="text-muted">Tiếng
                                                            Trung</small></td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['hr']['chinese']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['hr']['chinese'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['lm']['chinese']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['lm']['chinese'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['final']['chinese']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['final']['chinese'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><strong>4. Japanese</strong><br><small class="text-muted">Tiếng
                                                            Nhật</small></td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['hr']['japanese']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['hr']['japanese'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['lm']['japanese']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['lm']['japanese'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['final']['japanese']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['final']['japanese'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><strong>5. Computer skills</strong><br><small class="text-muted">Kỹ
                                                            năng vi tính</small></td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['hr']['computer']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['hr']['computer'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['lm']['computer']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['lm']['computer'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['final']['computer']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['final']['computer'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><strong>6. Behavior during interview</strong><br><small
                                                            class="text-muted">Ứng xử trong phỏng vấn</small></td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['hr']['behavior']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['hr']['behavior'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['lm']['behavior']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['lm']['behavior'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['final']['behavior']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['final']['behavior'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><strong>7. Characteristics</strong><br><small
                                                            class="text-muted">Tính cách</small></td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['hr']['characteristics']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['hr']['characteristics'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['lm']['characteristics']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['lm']['characteristics'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['final']['characteristics']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['final']['characteristics'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><strong>8. Communication skills</strong><br><small
                                                            class="text-muted">Kỹ năng giao tiếp</small></td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['hr']['communication']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['hr']['communication'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['lm']['communication']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['lm']['communication'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['final']['communication']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['final']['communication'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><strong>9. Motivation</strong><br><small class="text-muted">Động
                                                            lực ứng tuyển</small></td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['hr']['motivation']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['hr']['motivation'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['lm']['motivation']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['lm']['motivation'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['final']['motivation']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['final']['motivation'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><strong>10. Experience from previous jobs</strong><br><small
                                                            class="text-muted">Kinh nghiệm từ công việc trước đây</small>
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['hr']['experience']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['hr']['experience'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['lm']['experience']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['lm']['experience'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['final']['experience']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['final']['experience'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><strong>11. Customer handling experience</strong><br><small
                                                            class="text-muted">Kinh nghiệm giải quyết vấn đề khách
                                                            hàng</small></td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['hr']['customer']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['hr']['customer'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['lm']['customer']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['lm']['customer'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['final']['customer']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['final']['customer'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><strong>12. Flexibility</strong><br><small class="text-muted">Sự
                                                            linh hoạt, mềm mỏng trong công việc</small></td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['hr']['flexibility']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['hr']['flexibility'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['lm']['flexibility']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['lm']['flexibility'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['final']['flexibility']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['final']['flexibility'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><strong>13. Teamwork</strong><br><small class="text-muted">Tinh
                                                            thần tập thể</small></td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['hr']['teamwork']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['hr']['teamwork'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['lm']['teamwork']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['lm']['teamwork'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($evaluations['final']['teamwork']))
                                                            <span
                                                                class="rating-value">{{ $evaluations['final']['teamwork'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <!-- Tổng điểm -->
                                                <tr class="bg-light">
                                                    <td><strong>TỔNG ĐIỂM</strong></td>
                                                    <td class="text-center">
                                                        <span class="total-score">{{ $totalScores['hr'] }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="total-score">{{ $totalScores['lm'] }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="total-score">{{ $totalScores['final'] }}</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Đề xuất của người phỏng vấn -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card interview-info-card">
                                <div class="card-body">
                                    <h6 class="text-uppercase text-primary font-weight-bolder mb-3">Đề xuất của người phỏng
                                        vấn</h6>

                                    @if (isset($recommendations['hr']))
                                        <div class="recommendation-box">
                                            <h6 class="mb-2"><i class="fas fa-user-tie me-2"></i> HR</h6>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <p class="mb-2">
                                                        {{ $recommendations['hr']['action'] ?? 'Không có đề xuất' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    @if (isset($recommendations['hr']['propose_next_step']))
                                                        @if ($recommendations['hr']['propose_next_step'] == 'highly_recommend')
                                                            <span class="badge bg-success">Highly recommend</span>
                                                        @elseif($recommendations['hr']['propose_next_step'] == 'recommend')
                                                            <span class="badge bg-primary">Recommend</span>
                                                        @elseif($recommendations['hr']['propose_next_step'] == 'do_not_recommend')
                                                            <span class="badge bg-danger">Do not recommend</span>
                                                        @elseif($recommendations['hr']['propose_next_step'] == 'hold_consider')
                                                            <span class="badge bg-warning">Hold consider</span>
                                                        @elseif($recommendations['hr']['propose_next_step'] == 'other_position')
                                                            <span class="badge bg-info">Other position</span>
                                                            @if (isset($recommendations['hr']['other_position_detail']))
                                                                <p class="small text-muted mt-1">
                                                                    {{ $recommendations['hr']['other_position_detail'] }}
                                                                </p>
                                                            @endif
                                                        @endif
                                                    @else
                                                        <span class="badge bg-secondary">Chưa có đề xuất</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if (isset($recommendations['lm']))
                                        <div class="recommendation-box">
                                            <h6 class="mb-2"><i class="fas fa-user-tie me-2"></i> Line Manager</h6>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <p class="mb-2">
                                                        {{ $recommendations['lm']['action'] ?? 'Không có đề xuất' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    @if (isset($recommendations['lm']['propose_next_step']))
                                                        @if ($recommendations['lm']['propose_next_step'] == 'highly_recommend')
                                                            <span class="badge bg-success">Highly recommend</span>
                                                        @elseif($recommendations['lm']['propose_next_step'] == 'recommend')
                                                            <span class="badge bg-primary">Recommend</span>
                                                        @elseif($recommendations['lm']['propose_next_step'] == 'do_not_recommend')
                                                            <span class="badge bg-danger">Do not recommend</span>
                                                        @elseif($recommendations['lm']['propose_next_step'] == 'hold_consider')
                                                            <span class="badge bg-warning">Hold consider</span>
                                                        @elseif($recommendations['lm']['propose_next_step'] == 'other_position')
                                                            <span class="badge bg-info">Other position</span>
                                                            @if (isset($recommendations['lm']['other_position_detail']))
                                                                <p class="small text-muted mt-1">
                                                                    {{ $recommendations['lm']['other_position_detail'] }}
                                                                </p>
                                                            @endif
                                                        @endif
                                                    @else
                                                        <span class="badge bg-secondary">Chưa có đề xuất</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if (isset($recommendations['final']))
                                        <div class="recommendation-box">
                                            <h6 class="mb-2"><i class="fas fa-user-tie me-2"></i> Final</h6>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <p class="mb-2">
                                                        {{ $recommendations['final']['action'] ?? 'Không có đề xuất' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    @if (isset($recommendations['final']['propose_next_step']))
                                                        @if ($recommendations['final']['propose_next_step'] == 'highly_recommend')
                                                            <span class="badge bg-success">Highly recommend</span>
                                                        @elseif($recommendations['final']['propose_next_step'] == 'recommend')
                                                            <span class="badge bg-primary">Recommend</span>
                                                        @elseif($recommendations['final']['propose_next_step'] == 'do_not_recommend')
                                                            <span class="badge bg-danger">Do not recommend</span>
                                                        @elseif($recommendations['final']['propose_next_step'] == 'hold_consider')
                                                            <span class="badge bg-warning">Hold consider</span>
                                                        @elseif($recommendations['final']['propose_next_step'] == 'other_position')
                                                            <span class="badge bg-info">Other position</span>
                                                            @if (isset($recommendations['final']['other_position_detail']))
                                                                <p class="small text-muted mt-1">
                                                                    {{ $recommendations['final']['other_position_detail'] }}
                                                                </p>
                                                            @endif
                                                        @endif
                                                    @else
                                                        <span class="badge bg-secondary">Chưa có đề xuất</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if (!empty($candidate->reference_feedback))
                                        <div class="mt-3">
                                            <h6 class="text-uppercase font-weight-bolder">Phản hồi về thông tin tham khảo
                                            </h6>
                                            <div class="p-3 bg-light rounded">
                                                {{ $candidate->reference_feedback }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hiển thị CV nhúng nếu có -->
                    @if ($candidate->cv)
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card interview-info-card">
                                    <div class="card-body">
                                        <h6 class="text-uppercase text-primary font-weight-bolder mb-3">CV Preview</h6>
                                        <div class="cv-preview-container">
                                            <object data="{{ asset('storage/' . $candidate->cv) }}"
                                                type="application/pdf" width="100%" height="100%">
                                                <p>Trình duyệt của bạn không hỗ trợ nhúng PDF. <a
                                                        href="{{ asset('storage/' . $candidate->cv) }}"
                                                        target="_blank">Nhấn vào đây</a> để mở PDF trong tab mới.</p>
                                            </object>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-admin')
@endpush
