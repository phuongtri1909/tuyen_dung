@extends('admin.layouts.app')

@push('styles-admin')
@endpush
@section('content-auth')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-md-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="mb-0">Danh sách nhân viên</h5>
                        <div class="d-flex align-items-center flex-wrap">
                            <form action="{{ route('users.index') }}" method="GET" class="d-flex mb-2 mb-md-0">
                                <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Tìm kiếm..."
                                    value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-secondary btn-sm mb-0">Tìm</button>
                            </form>
                            @if (auth()->user()->role == 'admin')
                                <a href="{{ route('users.create') }}" class="btn bg-gradient-primary btn-sm mb-0 ms-2" type="button">
                                    <i class="fa-solid fa-plus"></i> Thêm nhân viên
                                </a>
                            @endif
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
                                        Tên
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Email
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Vai trò
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Phòng ban
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Ngày tạo/cập nhật
                                    </th>
                                    @if(auth()->user()->role == 'admin')
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        {{ __('action') }}
                                    </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0 ps-3">{{ $loop->iteration }}</p>
                                        </td>
                                        <td class="ps-4">
                                            <p class="text-xs font-weight-bold mb-0 width-100">{{ $user->name }}</p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0 width-100">{{ $user->email }}</p>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ 
                                                $user->role == 'admin' ? 'danger' : 
                                                ($user->role == 'hr' ? 'primary' : 
                                                ($user->role == 'lm' ? 'success' : 'warning')) 
                                            }}">
                                                {{ $user->role }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0 width-100">
                                                {{ $user->department->name ?? 'N/A' }}
                                            </p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0 width-300">
                                                Tạo: {{ $user->created_at }}
                                                <br>
                                                Cập nhật: {{ $user->updated_at }}
                                            </p>
                                        </td>
                                        @if (auth()->user()->role == 'admin')
                                            <td class="text-center d-flex justify-content-center">
                                                <a style="height:33px" href="{{ route('users.edit', $user->id) }}"
                                                    class="btn-success btn-sm px-3 me-2"><i
                                                        class="fa-regular fa-pen-to-square"></i></a>

                                                @include('admin.pages.components.delete-form', [
                                                    'id' => $user->id,
                                                    'route' => route('users.destroy', $user->id),
                                                    'message' => 'Bạn có chắc chắn muốn xóa nhân viên này?',
                                                ])
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Không có dữ liệu</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        
                        @if($users->hasPages())
                            {{ $users->links('admin.pages.components.paginate') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts-admin')
@endpush