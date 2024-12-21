<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="modal">
        <div class="logo">
            <h1>SOUNDHAVEN</h1>
        </div>

        <!-- Tampilkan error jika ada -->
        @if ($errors->has('login'))
            <div class="alert-failed">
                {{ $errors->first('login') }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="input-group">
                <input type="email" name="email" placeholder="Enter email" required>
            </div>

            <div class="input-group">
                <input type="password" name="password" id="password" placeholder="Enter password" required>
                <button type="button" class="toggle-password" onclick="togglePassword()">
                    👁️
                </button>
            </div>

            <button type="submit" class="sign-in-btn">Login</button>

            <div class="links">
                {{-- <label for="">Dont have an account ?</label> --}}
                <a href="{{ route('register') }}">Dont have an account ?</a>
            </div>
        </form>
    </div>
    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>
