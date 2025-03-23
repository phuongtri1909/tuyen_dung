@extends('admin.layouts.app')

@push('styles-admin')
@endpush

@section('content-auth')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0 px-3">
                    <h5 class="mb-0">Thêm ứng viên mới</h5>
                </div>
                <div class="card-body pt-4 p-3">

                    @include('admin.pages.components.success-error')

                    <form action="{{ route('candidates.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Họ tên ứng viên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                        id="full_name" name="full_name" value="{{ old('full_name') }}">
                                    @error('full_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="desired_position" class="form-label">Vị trí mong muốn <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('desired_position') is-invalid @enderror"
                                        id="desired_position" name="desired_position" value="{{ old('desired_position') }}">
                                    @error('desired_position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="outlet_department" class="form-label">Phòng ban ứng tuyển <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('outlet_department') is-invalid @enderror"
                                        id="outlet_department" name="outlet_department" value="{{ old('outlet_department') }}">
                                    @error('outlet_department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="employment_type" class="form-label">Loại hình công việc <span class="text-danger">*</span></label>
                                    <select class="form-select @error('employment_type') is-invalid @enderror" id="employment_type" name="employment_type">
                                        <option value="">Chọn loại hình</option>
                                        <option value="full-time" {{ old('employment_type') == 'full-time' ? 'selected' : '' }}>Toàn thời gian</option>
                                        <option value="casual-labor" {{ old('employment_type') == 'casual-labor' ? 'selected' : '' }}>Bán thời gian</option>
                                    </select>
                                    @error('employment_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="department_id" class="form-label">Phòng ban quản lý <span class="text-danger">*</span></label>
                                    <select class="form-select @error('department_id') is-invalid @enderror" id="department_id" name="department_id">
                                        <option value="">Chọn phòng ban</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Thêm trường upload CV -->
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="cv" class="form-label">CV (PDF)</label>
                                    <input type="file" class="form-control @error('cv') is-invalid @enderror"
                                        id="cv" name="cv" accept=".pdf">
                                    <small class="text-muted">Chỉ chấp nhận file PDF</small>
                                    @error('cv')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn bg-gradient-primary">Lưu thông tin</button>
                            <a href="{{ route('candidates.index') }}" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-admin')
@endpush