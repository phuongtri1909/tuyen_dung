@extends('admin.layouts.app')

@push('styles-admin')
@endpush
@section('content-auth')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-md-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="mb-0">Danh sách phòng ban</h5>
                        </div>
                        <div class="d-flex align-items-center flex-wrap">
                            <form action="{{ route('departments.index') }}" method="GET" class="d-flex mb-2 mb-md-0">
                                <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Tìm kiếm..."
                                    value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-secondary btn-sm mb-0">Tìm</button>
                            </form>
                            <a href="{{ route('departments.create') }}" class="btn bg-gradient-primary btn-sm mb-0 ms-2" type="button">
                                <i class="fa-solid fa-plus"></i> Thêm phòng ban
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">

                    @include('admin.pages.components.success-error')

                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        STT
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Tên phòng ban
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Ngày tạo/cập nhật
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        {{ __('action') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($departments as $department)
                                <tr>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0 ps-3">{{ $loop->iteration }}</p>
                                    </td>
                                    <td class="ps-4">
                                        <p class="text-xs font-weight-bold mb-0 width-100">{{ $department->name }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0 width-300">
                                         Tạo:   {{ $department->created_at }}
                                            <br>
                                        Cập nhật:   {{ $department->updated_at }}
                                        </p>
                                    </td>
                                    <td class="text-center d-flex justify-content-center">
                                       <a style="height:33px" href="{{ route('departments.edit', $department->id) }}" class="btn-success btn-sm px-3 me-2"><i class="fa-regular fa-pen-to-square"></i></a>
                                       @include('admin.pages.components.delete-form', ['id' => $department->id, 'route' => route('departments.destroy', $department->id),'message' => 'Bạn có chắc chắn muốn xóa phòng ban này?'])
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Không có dữ liệu</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        
                        @if($departments->hasPages())
                            {{ $departments->links('admin.pages.components.paginate') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts-admin')

@endpush