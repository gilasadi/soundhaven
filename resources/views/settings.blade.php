<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/settings.css') }}">
    <title>Settings</title>
</head>
<body>
    <header>
        <a href="{{ route('all-music') }}" class="logo">Logo</a>
        <ul>
            <li><a href="{{ route('all-music') }}">All Music</a></li>
            @if (auth()->user()->role === 'admin')
                <li><a href="{{ route('all-user') }}">All User</a></li>
            @else
                <li><a href="{{ route('myplaylist') }}">My Playlist</a></li>
                <li><a href="{{ route('myfavorit') }}">Liked Songs</a></li> <!-- Ditampilkan hanya untuk non-admin -->
            @endif
            <li><a href="{{ route('top-music') }}">Report</a></li>
            <img src="{{ Auth::user()->profile_image && Auth::user()->profile_image !== 'images/profile.jpg' ? asset('storage/' . Auth::user()->profile_image) : asset('images/profile.jpg') }}"
            alt="Profile Picture" class="user-pic" onclick="toggleMenu()">
        </ul>
        <div class="sub-menu-wrap" id="subMenu">
            <div class="sub-menu">
                <div class="user-info">
                    <img src="{{ Auth::user()->profile_image && Auth::user()->profile_image !== 'images/profile.jpg' ? asset('storage/' . Auth::user()->profile_image) : asset('images/profile.jpg') }}">
                    <h3>{{ Auth::user()->name }}</h3>
                </div>
                <hr>

                <a href="{{ route('user.settings') }}" class="sub-menu-link">
                    <img src="{{ asset('images/profile.png') }}" alt="">
                    <p>Edit Profile</p>
                    <span>></span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a href="#" onclick="logoutWithConfirmation(event)" class="sub-menu-link">
                    <img src="{{ asset('images/logout.png') }}" alt="">
                    <p>Logout</p>
                    <span>></span>
                </a>
            </div>
        </div>
    </header>

    <div class="container mt-5">
        <h1>Settings</h1>
        <!-- Profile Picture -->
        <div class="text-center mb-4">
            <img
            src="{{ Auth::user()->profile_image && Auth::user()->profile_image !== 'images/profile.jpg' ? asset('storage/' . Auth::user()->profile_image) : asset('images/profile.jpg') }}"
            alt="Profile Picture"
            class="rounded-circle mb-3"
            style="width: 150px; height: 150px;">
            <form action="{{ route('user.update-profile-image') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="file" name="profile_image" class="form-control mb-2" accept="image/*" required>
                <button type="submit" class="btn btn-primary">Update Profile Picture</button>
            </form>
        </div>

        <!-- User Information Form -->
        <form action="{{ route('user.update-email') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" readonly>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Email</button>
        </form>

        <!-- Change Password Button -->
        <button class="btn btn-warning mt-4" data-bs-toggle="modal" data-bs-target="#changePasswordModal">Change Password</button>

        <!-- Change Password Modal -->
        <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true" data-bs-theme="dark">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('user.change-password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                            </div>
                            <div class="mb-3">
                                <<label for="new_password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let subMenu = document.getElementById("subMenu");
        function toggleMenu(){
            subMenu.classList.toggle("open-menu");
        }
        // Tampilkan pesan error jika ada
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: `
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                `,
            });
        @endif

        // Tampilkan pesan sukses jika ada
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
            });
        @endif

        // Tampilkan pesan error khusus jika ada
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
            });
        @endif

        function logoutWithConfirmation(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: "You will be logged out!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, logout!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>
</body>
</html>
