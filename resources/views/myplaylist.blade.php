<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/myplaylist.css') }}">
    <title>My Playlist</title>
</head>
<body>
    <!-- Navbar -->
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

    <!-- Main Content -->
    <div class="container">
        <div class="header">
            <h1>My Playlists</h1>
            <button class="add-button" data-bs-toggle="modal" data-bs-target="#createPlaylistModal">+</button>
        </div>

        <!-- Modal for Creating Playlist -->
        <div class="modal fade" id="createPlaylistModal" tabindex="-1" aria-labelledby="createPlaylistModalLabel" aria-hidden="true" data-bs-theme="dark">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createPlaylistModalLabel">Create Playlist</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('playlists.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Playlist Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter playlist name" required>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Playlist Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create Playlist</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="playlists-grid">
            @foreach ($playlists as $playlist)
                <div class="playlist-card">
                    <div class="playlist-image">
                        @if ($playlist->image)
                            <img src="{{ asset('storage/' . $playlist->image) }}" alt="{{ $playlist->name }}">
                        @else
                            <img src="https://via.placeholder.com/150" alt="Default Image">
                        @endif
                    </div>
                    <div class="playlist-info">
                        <span class="playlist-title">{{ $playlist->name }}</span>
                        <div class="button-group">
                            <div class="action-buttons">
                                <form action="{{ route('playlists.destroy', $playlist->id) }}" method="POST" style="display: inline;" class="delete-playlist-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-remove" onclick="confirmDeletePlaylist(this)">Remove</button>
                                </form>
                                <button class="btn btn-view" data-bs-toggle="modal" data-bs-target="#playlistModal{{ $playlist->id }}">View Songs</button>
                            </div>
                            <button class="play-playlist" data-playlist="{{ json_encode($playlist->songs) }}">▶</button>
                        </div>
                    </div>
                </div>

                <!-- Modal untuk menampilkan lagu dalam playlist -->
                <div class="modal fade" id="playlistModal{{ $playlist->id }}" tabindex="-1"
                    aria-labelledby="playlistModalLabel{{ $playlist->id }}" aria-hidden="true" data-bs-theme="dark">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="playlistModalLabel{{ $playlist->id }}">
                                    Songs in "{{ $playlist->name }}"
                                </h5>
                            </div>
                            <div class="modal-body">
                                <ul class="list-group">
                                    @forelse ($playlist->songs as $song)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $song->title }} - {{ $song->artist }}
                                            <form
                                                action="{{ route('playlist.remove-song', ['playlist' => $playlist->id, 'song' => $song->id]) }}"
                                                method="POST"
                                                style="display:inline;"
                                                class="remove-song-form"
                                                data-song-title="{{ $song->title }}"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmRemove(this)">Remove</button>
                                            </form>
                                        </li>
                                    @empty
                                        <li class="list-group-item">No songs in this playlist.</li>
                                    @endforelse
                                </ul>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <div class="music-player">
        <img id="currentSongImage" src="https://via.placeholder.com/100" alt="Song Image" class="song-image">
        <div id= "title">
            <div id="currentSongTitle" class="song-title">No song selected</div>
            <div id="currentSongArtist" class="song-artist">-</div>
        </div>

        <div class="player-content">
            <div class="seekbar-container">
                <input type="range" id="seekbar" min="0" max="100" value="0" class="seekbar">
            </div>

            <div class="controls">
                <button id="shuffleButton">🔀</button>
                <button id="prevButton">⏮</button>
                <button id="playPauseButton">⏯</button>
                <button id="nextButton">⏭</button>
                <button id="repeatButton">🔁</button>
            </div>

            <!-- Seekbar -->
            <div class="seekbar-container">
                <div class="time-volume-container">
                    <span id="currentTime" class="time-display">00:00</span>
                    <div class="volume-control">
                        <label for="volumeControl">🔊</label>
                        <input type="range" id="volumeControl" min="0" max="1" step="0.1" value="1">
                    </div>
                    <span id="durationTime" class="time-display">00:00</span>
                </div>
            </div>
        </div>
    </div>
    <audio id="audioPlayer" controls style="display: none;" ></audio>

    <script src="{{ asset('js/myplaylist.js') }}"></script>
    <script>
        let subMenu = document.getElementById("subMenu");
        function toggleMenu(){
            subMenu.classList.toggle("open-menu");
        }

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

        function confirmRemove(button) {
            const form = button.closest('.remove-song-form'); // Ambil form terkait tombol
            const songTitle = form.getAttribute('data-song-title'); // Ambil judul lagu

            Swal.fire({
                title: `Remove "${songTitle}"?`,
                text: "Are you sure you want to remove this song from the playlist?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, remove it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Kirim formulir jika pengguna mengonfirmasi
                }
            });
        }

        function confirmDeletePlaylist(button) {
            const form = button.closest('.delete-playlist-form'); // Ambil formulir terkait tombol

            Swal.fire({
                title: 'Delete Playlist?',
                text: "Are you sure you want to delete this playlist? This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Kirim form jika pengguna mengonfirmasi
                }
            });
        }
    </script>
</body>
</html>
