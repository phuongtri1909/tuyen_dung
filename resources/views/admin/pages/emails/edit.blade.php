@extends('admin.layouts.app')

@push('styles-admin')
<!-- Thêm CKEditor -->
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<style>
    /* Đảm bảo editor hiển thị đúng */
    .ck-editor__editable_inline {
        min-height: 400px;
    }
</style>
@endpush

@section('content-auth')
<div class="row">
    <div class="col-12">
        <div class="card mb-4 mx-4">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5>Chỉnh sửa mẫu email - {{ strtoupper($template->role) }}</h5>
                    <a href="{{ route('email-templates.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('email-templates.update', $template->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group mb-3">
                        <label for="subject" class="form-label">Tiêu đề email</label>
                        <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject', $template->subject) }}">
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="content" class="form-label">Nội dung email</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="20">{{ old('content', $template->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Lưu thay đổi
                        </button>
                    </div>
                </form>
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

@push('scripts-admin')
<script>
    ClassicEditor
        .create(document.querySelector('#content'), {
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                'outdent', 'indent', '|',
                'blockQuote', 'insertTable', 'undo', 'redo'
            ]
        })
        .catch(error => {
            console.error(error);
        });
</script>
@endpush