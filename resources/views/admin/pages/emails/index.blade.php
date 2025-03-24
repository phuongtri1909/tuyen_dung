@extends('admin.layouts.app')

@section('content-auth')
<div class="row">
    <div class="col-12">
        <div class="card mb-4 mx-4">
            <div class="card-header pb-0">
                <h5>Quản lý mẫu email thông báo phỏng vấn</h5>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-3">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tên mẫu</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Vai trò</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tiêu đề</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($templates as $template)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $template->name }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $template->role == 'hr' ? 'primary' : ($template->role == 'lm' ? 'success' : 'warning') }}">
                                        {{ strtoupper($template->role) }}
                                    </span>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ Str::limit($template->subject, 50) }}</p>
                                </td>
                                <td class="align-middle">
                                    <a href="{{ route('email-templates.edit', $template->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit me-1"></i> Chỉnh sửa
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card mb-4 mx-4">
            <div class="card-header pb-0">
                <h5>Hướng dẫn sử dụng biến</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Biến</th>
                                <th>Mô tả</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>[candidate_name]</td><td>Tên ứng viên</td></tr>
                            <tr><td>[candidate_position]</td><td>Vị trí ứng tuyển</td></tr>
                            <tr><td>[department]</td><td>Phòng ban</td></tr>
                            <tr><td>[interview_date]</td><td>Ngày phỏng vấn</td></tr>
                            <tr><td>[interviewer_name]</td><td>Tên người phỏng vấn</td></tr>
                            <tr><td>[cv_link]</td><td>Link tải CV</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection