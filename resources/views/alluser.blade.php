<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/alluser.css') }}">
    <title>All Users</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert -->
</head>
<body>
    <header>
        <a href="{{ route('all-music') }}" class="logo">
            <img src="{{ asset('images/soundhaven.png') }}" alt="">
        </a>
        <ul>
            <li><a href="{{ route('all-music') }}">All Music</a></li>
            @if (auth()->user()->role === 'admin')
                <li><a href="{{ route('all-user') }}">All User</a></li>
            @else
                <li><a href="{{ route('myplaylist') }}">My Playlist</a></li>
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

    <div class="main-content">
        <div class="container">
            <h1>All Users</h1>

            <!-- Search Result Info -->
            @if (!empty($search))
                <p>Search Results for "<strong>{{ $search }}</strong>"</p>
                @if ($users->isEmpty())
                    <p>No users found.</p>
                @endif
            @endif

            <!-- User Table -->
            @if ($users->isNotEmpty())
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $user->id }})">Delete</button>
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <script>
        let subMenu = document.getElementById("subMenu");
        function toggleMenu(){
            subMenu.classList.toggle("open-menu");
        }
        // Fungsi SweetAlert untuk konfirmasi penghapusan
        function confirmDelete(userId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${userId}`).submit();
                }
            });
        }

        // Tampilkan SweetAlert untuk pesan sukses atau error
        document.addEventListener("DOMContentLoaded", function () {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session("success") }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session("error") }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif
        });

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
