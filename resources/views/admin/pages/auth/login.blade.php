<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng nhập - Hệ thống quản lý tuyển dụng</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            max-width: 450px;
            width: 100%;
            padding: 0;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(50, 50, 93, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
        }

        .login-header {
            background: linear-gradient(45deg, #4776E6, #8E54E9);
            padding: 30px;
            text-align: center;
            color: white;
        }

        .login-logo {
            width: 160px;
            margin-bottom: 15px;
        }

        .login-body {
            padding: 40px 30px;
            background: white;
        }

        .form-control {
            height: 50px;
            border-radius: 8px;
            box-shadow: none;
            margin-bottom: 20px;
            font-size: 15px;
            border: 1px solid #e1e1e1;
            padding-left: 15px;
        }

        .form-control:focus {
            border-color: #8E54E9;
            box-shadow: 0 0 0 0.2rem rgba(142, 84, 233, 0.25);
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
            color: #444;
        }

        .input-group-text {
            background: transparent;
            border-left: none;
            cursor: pointer;
        }

        .btn-login {
            height: 50px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 16px;
            background: linear-gradient(45deg, #4776E6, #8E54E9);
            border: none;
            box-shadow: 0 4px 10px rgba(74, 58, 255, 0.26);
            transition: all 0.3s ease;
        }

        .btn-login:hover,
        .btn-login:focus {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(74, 58, 255, 0.4);
            background: linear-gradient(45deg, #3D68D8, #7941D7);
        }

        .form-check-input:checked {
            background-color: #8E54E9;
            border-color: #8E54E9;
        }

        .invalid-feedback {
            font-size: 13px;
            font-weight: 400;
        }

        .alert {
            border-radius: 8px;
            font-size: 14px;
        }

        .forgot-password {
            font-size: 14px;
            color: #8E54E9;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .forgot-password:hover {
            color: #4776E6;
            text-decoration: underline;
        }

        @media (max-width: 576px) {
            .login-card {
                max-width: 100%;
                margin: 0 15px;
            }

            .login-body {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-center">

            <div class="login-card">
                <div class="login-header">
                    <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Logo" class="login-logo">
                    <h4 class="mb-0">Hệ thống quản lý tuyển dụng</h4>
                </div>
                <div class="login-body">
                    <h5 class="mb-4 text-center">Đăng nhập vào hệ thống</h5>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <i class="fa fa-user text-muted"></i>
                                </span>
                                <input type="text"
                                    class="mb-0 form-control form-control-sm border-start-0 @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}"
                                    placeholder="Nhập Email" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <i class="fa fa-lock text-muted"></i>
                                </span>
                                <input type="password"
                                    class="mb-0 form-control form-control-sm border-start-0 border-end-0 @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Nhập mật khẩu" required>
                                <span class="input-group-text bg-transparent border-start-0" id="toggle-password">
                                    <i class="fa fa-eye-slash text-muted" id="eye-icon"></i>
                                </span>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Ghi nhớ đăng nhập
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-login w-100">
                            <i class="fa fa-sign-in-alt me-2"></i>Đăng nhập
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Xử lý hiện/ẩn mật khẩu
            const togglePassword = document.getElementById('toggle-password');
            const passwordField = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            if (togglePassword) {
                togglePassword.addEventListener('click', function() {
                    if (passwordField.type === 'password') {
                        passwordField.type = 'text';
                        eyeIcon.classList.remove('fa-eye-slash');
                        eyeIcon.classList.add('fa-eye');
                    } else {
                        passwordField.type = 'password';
                        eyeIcon.classList.remove('fa-eye');
                        eyeIcon.classList.add('fa-eye-slash');
                    }
                });
            }

            // Tự động ẩn alert sau 5 giây
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
</body>

</html>
