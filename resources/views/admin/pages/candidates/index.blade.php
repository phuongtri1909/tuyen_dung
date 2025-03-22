@extends('admin.layouts.app')

@push('styles-admin')
@endpush
@section('content-auth')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-md-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="mb-0">Danh sách ứng viên</h5>
                        <div class="d-flex align-items-center flex-wrap">
                            <form action="{{ route('candidates.index') }}" method="GET" class="d-flex mb-2 mb-md-0">
                                <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Tìm kiếm..."
                                    value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-secondary btn-sm mb-0">Tìm</button>
                            </form>
                            @if (auth()->user()->role == 'admin' || auth()->user()->role == 'hr')
                                <a href="{{ route('candidates.create') }}" class="btn bg-gradient-primary btn-sm mb-0 ms-2" type="button">
                                    <i class="fa-solid fa-plus"></i> Thêm ứng viên
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
                                        Họ tên
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Vị trí mong muốn
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Phòng ban ứng tuyển
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Loại hình công việc
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Phòng ban quản lý
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        {{ __('action') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($candidates as $candidate)
                                    <tr>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0 ps-3">{{ $loop->iteration }}</p>
                                        </td>
                                        <td class="ps-4">
                                            <p class="text-xs font-weight-bold mb-0 width-100">{{ $candidate->full_name }}</p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0 width-100">{{ $candidate->desired_position }}</p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0 width-100">{{ $candidate->outlet_department }}</p>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ 
                                                $candidate->employment_type == 'full-time' ? 'primary' : 'info' 
                                            }}">
                                                {{ $candidate->employment_type == 'full-time' ? 'Toàn thời gian' : 'Bán thời gian' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0 width-100">
                                                {{ $candidate->department->name ?? 'N/A' }}
                                            </p>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex align-items-start">

                                                @if(auth()->user()->role != 'admin')
                                                    <a href="{{ route('candidates.interview',$candidate->id) }}"
                                                        class="btn-primary btn-sm px-3 me-2" title="Phỏng vấn">
                                                        <i class="fa-solid fa-users-viewfinder"></i>
                                                    </a>
                                                @endif

                                                <a href="{{ route('candidates.show', $candidate->id) }}"
                                                    class="btn-info btn-sm px-3 me-2" title="Xem chi tiết">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                                
                                                @if (auth()->user()->role == 'admin')
                                                    <a href="{{ route('candidates.edit', $candidate->id) }}"
                                                        class="btn-success btn-sm px-3 me-2" title="Chỉnh sửa">
                                                        <i class="fa-regular fa-pen-to-square"></i>
                                                    </a>

                                                    @include('admin.pages.components.delete-form', [
                                                        'id' => $candidate->id,
                                                        'route' => route('candidates.destroy', $candidate->id),
                                                        'message' => 'Bạn có chắc chắn muốn xóa ứng viên này?',
                                                    ])
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Không có dữ liệu</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        
                        @if($candidates->hasPages())
                            {{ $candidates->links('admin.pages.components.paginate') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts-admin')
@endpush