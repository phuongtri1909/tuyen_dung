<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-white"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="d-flex m-0 justify-content-center text-wrap" href="{{ route('admin.dashboard') }}">
            <img class="logo-d1-concepts" src="{{ asset('assets/images/logo/logo.png') }}"
                alt="logo-d1-concepts">
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteNamed('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-gauge-high text-dark icon-sidebar"></i>
                    </div>
                    <span class="nav-link-text ms-1">{{ __('dashboard') }}</span>
                </a>
            </li>


            <li class="nav-item mt-2">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Chức năng</h6>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteNamed('candidates.*') ? 'active' : '' }}"
                    href="{{ route('candidates.index') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-users text-dark icon-sidebar"></i>
                    </div>
                    <span class="nav-link-text ms-1">Ứng viên</span>
                </a>
            </li>

            @if (auth()->user()->role == 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteNamed('users.*') ? 'active' : '' }}"
                        href="{{ route('users.index') }}">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-users-line text-dark icon-sidebar"></i>
                        </div>
                        <span class="nav-link-text ms-1">Nhân sự</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteNamed('departments.*') ? 'active' : '' }}"
                        href="{{ route('departments.index') }}">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fa-regular fa-building text-dark icon-sidebar"></i>
                        </div>
                        <span class="nav-link-text ms-1">Phòng ban</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('email-templates.*') ? 'active' : '' }}" href="{{ route('email-templates.index') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-envelope text-dark"></i>
                        </div>
                        <span class="nav-link-text ms-1">Mẫu Email</span>
                    </a>
                </li>
            @endif


            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Tài khoản</h6>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-right-from-bracket text-dark"></i>
                    </div>
                    <span class="nav-link-text ms-1">Đăng xuất</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
