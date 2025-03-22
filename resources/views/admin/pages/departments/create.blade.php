@extends('admin.layouts.app')

@push('styles-admin')
@endpush

@section('content-auth')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0 px-3">
                    <h5 class="mb-0">Thêm phòng ban</h5>
                </div>
                <div class="card-body pt-4 p-3">

                    @include('admin.pages.components.success-error')

                    <form action="{{ route('departments.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên phòng ban</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn bg-gradient-primary">Lưu</button>
                            <a href="{{ route('departments.index') }}" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-admin')
@endpush