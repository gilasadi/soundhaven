<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    <title>Register</title>
</head>
<body>
    <div class="blue-bar"></div>
    <div class="form-container">
        <div class="form-header">
            <div class="logo">
                <span class="logo-text">Music Player</span>
            </div>
        </div>

        <!-- Pesan Error -->
        @if ($errors->any())
            <div class="error-message" style="color: red; text-align: center; margin-bottom: 1em;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="form-group">
                <input type="text" name="name" placeholder="Enter name here" required>
            </div>

            <div class="verification-group">
                <input type="email" name="email" placeholder="Enter email here" required>
            </div>

            <div class="form-group">
                <input type="password" name="password" placeholder="Enter password" required>
            </div>

            <div class="form-group">
                <input type="password" name="password_confirmation" placeholder="Confirm password" required>
            </div>

            <button type="submit" class="register-btn">Register</button>
        </form>
    </div>
</body>
</html>
