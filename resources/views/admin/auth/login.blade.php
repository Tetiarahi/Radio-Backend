<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Radio App</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        .login-card {
            width: 100%;
            max-width: 440px;
            padding: 40px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }
        .login-logo {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            box-shadow: 0 8px 24px rgba(108, 99, 255, 0.4);
            margin-bottom: 20px;
        }
        .login-title {
            font-size: 28px;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>

    <div class="glass-card login-card">
        <div class="login-header">
            <div class="login-logo">
                <i class="fa-solid fa-radio"></i>
            </div>
            <h1 class="login-title">Welcome Back</h1>
            <p style="color: var(--text-secondary);">Sign in to manage the Radio App platform</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" style="padding: 12px 18px; font-size: 14px;">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success" style="padding: 12px 18px; font-size: 14px;">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input class="form-control" type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@radioapp.com">
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input class="form-control" type="password" id="password" name="password" required placeholder="••••••••">
            </div>

            <div class="form-group" style="display: flex; align-items: center; justify-content: space-between;">
                <label class="checkbox-label">
                    <input class="checkbox-control" type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 16px; font-size: 16px; margin-top: 10px;">
                <span>Sign In to Admin</span>
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>
    </div>

</body>
</html>
