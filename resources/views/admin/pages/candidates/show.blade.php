@extends('admin.layouts.app')

@push('styles-admin')
@endpush

@section('content-auth')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0 px-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Thông tin ứng viên</h5>
                        <div>
                            @if (auth()->user()->role == 'admin')
                                <a href="{{ route('candidates.edit', $candidate->id) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-edit me-2"></i>Chỉnh sửa
                                </a>
                            @endif
                            <a href="{{ route('candidates.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-4 p-3">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <h6 class="text-uppercase text-body text-xs font-weight-bolder">Thông tin cơ bản</h6>
                            <ul class="list-group">
                                <li class="list-group-item border-0 ps-0 pt-0 text-sm">
                                    <strong class="text-dark">Họ tên:</strong> &nbsp; {{ $candidate->full_name }}
                                </li>
                                <li class="list-group-item border-0 ps-0 text-sm">
                                    <strong class="text-dark">Vị trí mong muốn:</strong> &nbsp; {{ $candidate->desired_position }}
                                </li>
                                <li class="list-group-item border-0 ps-0 text-sm">
                                    <strong class="text-dark">Phòng ban ứng tuyển:</strong> &nbsp; {{ $candidate->outlet_department }}
                                </li>
                                <li class="list-group-item border-0 ps-0 text-sm">
                                    <strong class="text-dark">Loại hình công việc:</strong> &nbsp; 
                                    <span class="badge bg-{{ 
                                        $candidate->employment_type == 'full-time' ? 'primary' : 'info'
                                    }}">
                                        {{ $candidate->employment_type == 'full-time' ? 'Toàn thời gian' : 'Bán thời gian' }}
                                    </span>
                                </li>
                                <li class="list-group-item border-0 ps-0 text-sm">
                                    <strong class="text-dark">Phòng ban quản lý:</strong> &nbsp; {{ $candidate->department->name ?? 'N/A' }}
                                </li>
                            </ul>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <h6 class="text-uppercase text-body text-xs font-weight-bolder">Thông tin đánh giá</h6>
                            <ul class="list-group">
                                <li class="list-group-item border-0 ps-0 pt-0 text-sm">
                                    <strong class="text-dark">HR đánh giá:</strong> &nbsp; 
                                    {{ $candidate->hr_name ? $candidate->hr_name . ' (' . $candidate->hr_date . ')' : 'Chưa đánh giá' }}
                                </li>
                                <li class="list-group-item border-0 ps-0 text-sm">
                                    <strong class="text-dark">LM đánh giá:</strong> &nbsp; 
                                    {{ $candidate->lm_name ? $candidate->lm_name . ' (' . $candidate->lm_date . ')' : 'Chưa đánh giá' }}
                                </li>
                                <li class="list-group-item border-0 ps-0 text-sm">
                                    <strong class="text-dark">Final đánh giá:</strong> &nbsp; 
                                    {{ $candidate->final_name ? $candidate->final_name . ' (' . $candidate->final_date . ')' : 'Chưa đánh giá' }}
                                </li>
                                <li class="list-group-item border-0 ps-0 text-sm">
                                    <strong class="text-dark">Tham khảo phản hồi:</strong> &nbsp; 
                                    {{ $candidate->reference_feedback ?: 'Không có' }}
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <h6 class="text-uppercase text-body text-xs font-weight-bolder">Khả năng làm việc</h6>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td class="ps-0">
                                                <span class="text-dark font-weight-bold">Có thể làm việc vào ngày lễ:</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge bg-{{ $candidate->can_work_holidays ? 'success' : 'danger' }}">
                                                    {{ $candidate->can_work_holidays ? 'Có' : 'Không' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ps-0">
                                                <span class="text-dark font-weight-bold">Có thể làm việc nhiều ca:</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge bg-{{ $candidate->can_work_different_shifts ? 'success' : 'danger' }}">
                                                    {{ $candidate->can_work_different_shifts ? 'Có' : 'Không' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ps-0">
                                                <span class="text-dark font-weight-bold">Có thể làm việc ca phân chia:</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge bg-{{ $candidate->can_work_split_shifts ? 'success' : 'danger' }}">
                                                    {{ $candidate->can_work_split_shifts ? 'Có' : 'Không' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ps-0">
                                                <span class="text-dark font-weight-bold">Có thể làm việc ngoài giờ:</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge bg-{{ $candidate->can_work_overtime ? 'success' : 'danger' }}">
                                                    {{ $candidate->can_work_overtime ? 'Có' : 'Không' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ps-0">
                                                <span class="text-dark font-weight-bold">Có thể làm việc ca đêm:</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge bg-{{ $candidate->can_work_late_shift ? 'success' : 'danger' }}">
                                                    {{ $candidate->can_work_late_shift ? 'Có' : 'Không' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ps-0">
                                                <span class="text-dark font-weight-bold">Số ngày thông báo trước:</span>
                                            </td>
                                            <td class="text-end">
                                                {{ $candidate->notice_days ?? 'N/A' }} ngày
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ps-0">
                                                <span class="text-dark font-weight-bold">Ngày có thể bắt đầu:</span>
                                            </td>
                                            <td class="text-end">
                                                {{ $candidate->available_date ? date('d/m/Y', strtotime($candidate->available_date)) : 'N/A' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ps-0">
                                                <span class="text-dark font-weight-bold">Mức lương tối thiểu:</span>
                                            </td>
                                            <td class="text-end">
                                                {{ number_format($candidate->min_salary ?? 0) }} VNĐ
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-admin')
@endpush