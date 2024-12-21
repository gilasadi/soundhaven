<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/report.css') }}">
    <title>All Music</title>
    <style>

    </style>
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

    <div class="content">
        <div class="container my-5">
            <h1 class="text-center">Top Music by Likes</h1>

            <!-- Grafik Barchart -->
            <div class="my-4">
                <canvas id="topMusicChart" class="canvas"></canvas>
            </div>

            <!-- Tabel Laporan -->
            <div class="my-4">
                <h2>Report</h2>
                <table class="table table-bordered">
                    <thead class="head-table">
                        <tr>
                            <th>Title</th>
                            <th>Artist</th>
                            <th>Likes</th>
                        </tr>
                    </thead>
                    <tbody class="body-table">
                        @foreach ($topSongs as $song)
                            <tr>
                                <td>{{ $song->title }}</td>
                                <td>{{ $song->artist }}</td>
                                <td class="likes">{{ $song->likes_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Tombol Cetak -->
            <div class="my-3 text-center">
                <a href="{{ route('topmusic.report') }}" target="_blank" class="btn btn-primary">Cetak Laporan</a>
            </div>
        </div>
    </div>

    <!-- Script untuk Chart.js -->
    <script>
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

        let subMenu = document.getElementById("subMenu");
        function toggleMenu(){
            subMenu.classList.toggle("open-menu");
        }

        window.addEventListener("scroll", function(){
            var header = document.querySelector("header");
            header.classList.toggle("sticky", window.scrollY > 0 )
        })
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('topMusicChart').getContext('2d');
            const topSongs = @json($topSongs); // Data dari backend
            const titles = topSongs.map(song => song.title); // Judul lagu
            const likes = topSongs.map(song => song.likes_count); // Jumlah likes

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: titles, // Sumbu X: Judul lagu
                    datasets: [{
                        label: 'Total Likes',
                        data: likes, // Sumbu Y: Jumlah likes
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            ticks: {
                                color: '#ffffff', // Ubah warna tulisan pada sumbu X menjadi putih
                            }
                        },
                        y: {
                            ticks: {
                                color: '#ffffff', // Ubah warna tulisan pada sumbu Y menjadi putih
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: '#ffffff' // Ubah warna label legend menjadi putih
                            }
                        }
                    }
                }
            });
        });

    </script>
</body>
</html>
