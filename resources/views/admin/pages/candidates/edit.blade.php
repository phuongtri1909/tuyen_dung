@extends('admin.layouts.app')

@push('styles-admin')
@endpush

@section('content-auth')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0 px-3">
                    <h5 class="mb-0">Chỉnh sửa ứng viên: {{ $candidate->full_name }}</h5>
                </div>
                <div class="card-body pt-4 p-3">

                    @include('admin.pages.components.success-error')

                    <form action="{{ route('candidates.update', $candidate->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Họ tên ứng viên <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                        id="full_name" name="full_name"
                                        value="{{ old('full_name', $candidate->full_name) }}">
                                    @error('full_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="desired_position" class="form-label">Vị trí mong muốn <span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control @error('desired_position') is-invalid @enderror"
                                        id="desired_position" name="desired_position"
                                        value="{{ old('desired_position', $candidate->desired_position) }}">
                                    @error('desired_position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="outlet_department" class="form-label">Phòng ban ứng tuyển <span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control @error('outlet_department') is-invalid @enderror"
                                        id="outlet_department" name="outlet_department"
                                        value="{{ old('outlet_department', $candidate->outlet_department) }}">
                                    @error('outlet_department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="employment_type" class="form-label">Loại hình công việc <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('employment_type') is-invalid @enderror"
                                        id="employment_type" name="employment_type">
                                        <option value="">Chọn loại hình</option>
                                        <option value="full-time"
                                            {{ old('employment_type', $candidate->employment_type) == 'full-time' ? 'selected' : '' }}>
                                            Toàn thời gian</option>
                                        <option value="casual-labor"
                                            {{ old('employment_type', $candidate->employment_type) == 'casual-labor' ? 'selected' : '' }}>
                                            Bán thời gian</option>

                                    </select>
                                    @error('employment_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="department_id" class="form-label">Phòng ban quản lý <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('department_id') is-invalid @enderror"
                                        id="department_id" name="department_id">
                                        <option value="">Chọn phòng ban</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}"
                                                {{ old('department_id', $candidate->department_id) == $department->id ? 'selected' : '' }}>
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

                                    @if ($candidate->cv)
                                        <div class="mt-2">
                                            <span class="badge bg-info">CV hiện tại: {{ basename($candidate->cv) }}</span>
                                            <a href="{{ asset('storage/' . $candidate->cv) }}" target="_blank"
                                                class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> Xem
                                            </a>
                                            <div class="form-check mt-1">
                                                <input class="form-check-input" type="checkbox" id="remove_cv"
                                                    name="remove_cv" value="1">
                                                <label class="form-check-label" for="remove_cv">
                                                    Xóa CV hiện tại
                                                </label>
                                            </div>
                                        </div>
                                    @else
                                        <div class="mt-2">
                                            <span class="badge bg-warning">Chưa có CV</span>
                                        </div>
                                    @endif

                                    @error('cv')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <h5 class="mt-4 mb-3">Lịch phỏng vấn</h5>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="hr_interview_date" class="form-label">Ngày phỏng vấn HR</label>
                                    <input type="date"
                                        class="form-control @error('hr_interview_date') is-invalid @enderror"
                                        id="hr_interview_date" name="hr_interview_date"
                                        value="{{ old('hr_interview_date', $candidate->hr_interview_date ? \Carbon\Carbon::parse($candidate->hr_interview_date)->format('Y-m-d') : '') }}">
                                    @error('hr_interview_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="hr_interviewer_id" class="form-label">Người phỏng vấn (HR)</label>
                                    <select class="form-select @error('hr_interviewer_id') is-invalid @enderror"
                                        id="hr_interviewer_id" name="hr_interviewer_id">
                                        <option value="">Chọn người phỏng vấn</option>
                                        @foreach ($hrUsers as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('hr_interviewer_id', $candidate->hr_interviewer_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} - {{ $user->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('hr_interviewer_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="hr_notified" name="hr_notified"
                                        value="1" {{ old('hr_notified', $candidate->hr_notified) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="hr_notified">
                                        Đã thông báo
                                    </label>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="lm_interview_date" class="form-label">Ngày phỏng vấn LM</label>
                                    <input type="date"
                                        class="form-control @error('lm_interview_date') is-invalid @enderror"
                                        id="lm_interview_date" name="lm_interview_date"
                                        value="{{ old('lm_interview_date', $candidate->lm_interview_date ? \Carbon\Carbon::parse($candidate->lm_interview_date)->format('Y-m-d') : '') }}">
                                    @error('lm_interview_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="lm_interviewer_id" class="form-label">Người phỏng vấn (LM)</label>
                                    <select class="form-select @error('lm_interviewer_id') is-invalid @enderror"
                                        id="lm_interviewer_id" name="lm_interviewer_id">
                                        <option value="">Chọn người phỏng vấn</option>
                                        @foreach ($lmUsers as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('lm_interviewer_id', $candidate->lm_interviewer_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} - {{ $user->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('lm_interviewer_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="lm_notified" name="lm_notified"
                                        value="1" {{ old('lm_notified', $candidate->lm_notified) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="lm_notified">
                                        Đã thông báo
                                    </label>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="final_interview_date" class="form-label">Ngày phỏng vấn Final</label>
                                    <input type="date"
                                        class="form-control @error('final_interview_date') is-invalid @enderror"
                                        id="final_interview_date" name="final_interview_date"
                                        value="{{ old('final_interview_date', $candidate->final_interview_date ? \Carbon\Carbon::parse($candidate->final_interview_date)->format('Y-m-d') : '') }}">
                                    @error('final_interview_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="final_interviewer_id" class="form-label">Người phỏng vấn (Final)</label>
                                    <select class="form-select @error('final_interviewer_id') is-invalid @enderror"
                                        id="final_interviewer_id" name="final_interviewer_id">
                                        <option value="">Chọn người phỏng vấn</option>
                                        @foreach ($finalUsers as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('final_interviewer_id', $candidate->final_interviewer_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} - {{ $user->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('final_interviewer_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="final_notified"
                                        name="final_notified" value="1"
                                        {{ old('final_notified', $candidate->final_notified) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="final_notified">
                                        Đã thông báo
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn bg-gradient-primary">Cập nhật thông tin</button>
                            <a href="{{ route('candidates.index') }}" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-admin')
    <script>
        // Xử lý hiển thị các phần tử liên quan đến CV
        document.addEventListener('DOMContentLoaded', function() {
            const cvInput = document.getElementById('cv');
            const removeCvCheckbox = document.getElementById('remove_cv');

            if (cvInput && removeCvCheckbox) {
                // Nếu người dùng đã chọn file mới, tự động check vào ô xóa CV cũ
                cvInput.addEventListener('change', function() {
                    if (this.files.length > 0) {
                        removeCvCheckbox.checked = true;
                    }
                });
            }
        });
    </script>
@endpush
