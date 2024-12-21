<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/all-music.css') }}">
    <title>All Music</title>
</head>
<body>
    <header>
        <a href="{{ route('all-music') }}" class="logo">
            <img src="{{ asset('images/soundhaven.png') }}" alt="">
        </a>
        <form action="{{ route('all-music') }}" method="GET" class="d-flex mx-auto">
            <input class="form-control me-2" type="search" name="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-info" type="submit">Search</button>
        </form>
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

    <div class="container">
        <div class="header">
            <h1>All Music</h1>
            <button class="add-btn" data-bs-toggle="modal" data-bs-target="#addSongModal">+</button>
        </div>
        <div id="alert-container"></div>

        <!-- Top 5 Music Section -->
        @if(request('search'))
            <h2>Search Results for "{{ request('search') }}"</h2>
            <div class="music-grid">
                @forelse ($songs as $song)
                    <div class="music-card">
                        <div class="music-image-container">
                            <img src="{{ asset('storage/' . $song->image) }}" alt="{{ $song->title }}" class="music-image">
                            <button class="play-btn"
                                onclick="playSong('{{ asset('storage/' . $song->file) }}', '{{ $song->title }}', '{{ $song->artist }}', '{{ asset('storage/' . $song->image) }}')">
                                ▶
                            </button>
                        </div>
                        <h3 class="music-title">{{ $song->title }}</h3>
                        <p class="music-artist">{{ $song->artist }}</p>
                        <div class="card-controls">
                            @if (auth()->user()->role !== 'admin')
                                <button class="like-btn" onclick="toggleLike(event, {{ $song->id }})">Like</button>
                                <span id="like-count-{{ $song->id }}" class="heart-count">❤️{{ $song->likes_count }}</span>
                                <form action="{{ route('playlist.add-song') }}" method="POST" class="playlist-form">
                                    @csrf
                                    <input type="hidden" name="song_id" value="{{ $song->id }}">
                                    <select name="playlist_id" required class="santai-dropdown">
                                        @foreach(auth()->user()->playlists as $playlist)
                                            <option value="{{ $playlist->id }}">{{ $playlist->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="add-playlist-btn">Add to Playlist</button>
                                </form>
                            @else
                                <form id="delete-song-form-{{ $song->id }}" action="{{ route('songs.delete', $song->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $song->id }})">Delete</button>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="not-found">No songs found.</p>
                @endforelse
            </div>
        @else
            <!-- Top 5 Music Section -->
            <h2>Top 5 Music</h2>
            <div class="music-grid">
                @forelse ($topSongs as $song)
                    <div class="music-card">
                        <div class="music-image-container">
                            <img src="{{ asset('storage/' . $song->image) }}" alt="{{ $song->title }}" class="music-image">
                            <button class="play-btn"
                                onclick="playSong('{{ asset('storage/' . $song->file) }}', '{{ $song->title }}', '{{ $song->artist }}', '{{ asset('storage/' . $song->image) }}')">
                                ▶
                            </button>
                        </div>
                        <h3 class="music-title">{{ $song->title }}</h3>
                        <p class="music-artist">{{ $song->artist }}</p>
                        <div class="card-controls">
                            <div class="like-row">
                                @if (auth()->user()->role !== 'admin')
                                    <button class="like-btn" onclick="toggleLike(event, {{ $song->id }})">Like</button>
                                    <span id="like-count-{{ $song->id }}" class="heart-count">❤️{{ $song->likes_count }}</span>
                                @endif
                            </div>
                            <div class="dropdown-row">
                                @if (auth()->user()->role !== 'admin')
                                    <form action="{{ route('playlist.add-song') }}" method="POST" class="playlist-form">
                                        @csrf
                                        <input type="hidden" name="song_id" value="{{ $song->id }}">
                                        <select name="playlist_id" required class="santai-dropdown">
                                            @foreach(auth()->user()->playlists as $playlist)
                                                <option value="{{ $playlist->id }}">{{ $playlist->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="add-playlist-btn">Add to Playlist</button>
                                    </form>
                                @else
                                    <form action="{{ route('songs.delete', $song->id) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="not-found">No songs found.</p>
                @endforelse
            </div>

            <h2>Recent Music</h2>
            <div class="music-grid">
                @forelse ($recentSongs as $song)
                    <div class="music-card">
                        <div class="music-image-container">
                            <img src="{{ asset('storage/' . $song->image) }}" alt="{{ $song->title }}" class="music-image">
                            <button class="play-btn"
                                onclick="playSong('{{ asset('storage/' . $song->file) }}', '{{ $song->title }}', '{{ $song->artist }}', '{{ asset('storage/' . $song->image) }}')">
                                ▶
                            </button>
                        </div>
                        <h3 class="music-title">{{ $song->title }}</h3>
                        <p class="music-artist">{{ $song->artist }}</p>
                        <div class="card-controls">
                            <div class="like-row">
                                @if (auth()->user()->role !== 'admin')
                                    <button class="like-btn" onclick="toggleLike(event, {{ $song->id }})">Like</button>
                                    <span id="like-count-{{ $song->id }}" class="heart-count">♥ {{ $likesCount[$song->id] ?? 0 }}</span>
                                @endif
                            </div>
                            <div class="dropdown-row">
                                @if (auth()->user()->role !== 'admin')
                                    <form action="{{ route('playlist.add-song') }}" method="POST" class="playlist-form">
                                        @csrf
                                        <input type="hidden" name="song_id" value="{{ $song->id }}">
                                        <select name="playlist_id" required class="santai-dropdown">
                                            @foreach(auth()->user()->playlists as $playlist)
                                                <option value="{{ $playlist->id }}">{{ $playlist->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="add-playlist-btn">Add to Playlist</button>
                                    </form>
                                @else
                                    <form id="delete-song-form-{{ $song->id }}" action="{{ route('songs.delete', $song->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                    <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $song->id }})">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="not-found">No songs found.</p>
                @endforelse
            </div>
        @endif

        <!-- Modal Add Song -->
        <div class="modal fade" id="addSongModal" tabindex="-1" aria-labelledby="addSongModalLabel" aria-hidden="true" data-bs-theme="dark">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSongModalLabel">Add New Song</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('songs.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="artist" class="form-label">Artist</label>
                                <input type="text" class="form-control" id="artist" name="artist" required>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Image (JPG, PNG)</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                            </div>
                            <div class="mb-3">
                                <label for="file" class="form-label">Music File (MP3)</label>
                                <input type="file" class="form-control" id="file" name="file" accept=".mp3" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Song</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <div class="music-player">
        <img id="currentSongImage" src="https://via.placeholder.com/100" alt="Song Image" class="song-image">
        <div id= "title">
            <div id="currentSong" class="song-title">No song selected</div>
            <div id="currentSongArtist" class="song-artist">-</div>
        </div>

        <div class="player-content">
            <div class="seekbar-container">
                <input type="range" id="seekbar" min="0" max="100" value="0" class="seekbar">
            </div>

            <div class="controls">
                {{-- <button id="shuffleButton">🔀</button> --}}
                {{-- <button id="prevButton">⏮</button> --}}
                <button id="playPauseButton">⏯</button>
                {{-- <button id="nextButton">⏭</button> --}}
                <button id="repeatButton" class="repeat-btn">🔁</button>
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

    {{-- <div class="music-player">
        <div id="currentSong">No song selected</div>
        <audio id="audioPlayer" controls></audio>
    </div> --}}

    {{-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // SweetAlert for Success Messages
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session("success") }}',
                    timer: 3000,
                    showConfirmButton: false,
                });
            @endif

            // SweetAlert for Error Messages
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session("error") }}',
                    timer: 3000,
                    showConfirmButton: false,
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

        function confirmDelete(songId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-song-form-${songId}`).submit();
                }
            });
        }

        window.addEventListener("scroll", function(){
            var header = document.querySelector("header");
            header.classList.toggle("sticky", window.scrollY > 0 )
        })

        let subMenu = document.getElementById("subMenu");

        function toggleMenu(){
            subMenu.classList.toggle("open-menu");
        }

        document.addEventListener("DOMContentLoaded", function () {
            const audioPlayer = document.getElementById('audioPlayer');
            const currentSongImage = document.getElementById('currentSongImage');
            const currentSongTitle = document.getElementById('currentSong');
            const currentSongArtist = document.getElementById('currentSongArtist');
            const playPauseButton = document.getElementById('playPauseButton');
            const volumeControl = document.getElementById('volumeControl');
            const repeatButton = document.getElementById('repeatButton');
            const seekbar = document.getElementById('seekbar');
            const currentTimeDisplay = document.getElementById('currentTime');
            const durationTimeDisplay = document.getElementById('durationTime');

            let isPlaying = false;
            let isRepeating = false;

            function formatTime(seconds) {
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = Math.floor(seconds % 60);
                return `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
            }

            function playSong(file, title, artist, image) {
                audioPlayer.src = file;
                currentSongTitle.innerText = title;
                currentSongArtist.innerText = artist;
                currentSongImage.src = image;

                audioPlayer.play();
                isPlaying = true;
                updatePlayPauseButton();
            }

            function togglePlayPause() {
                if (isPlaying) {
                    audioPlayer.pause();
                } else {
                    audioPlayer.play();
                }
                isPlaying = !isPlaying;
                updatePlayPauseButton();
            }

            function updatePlayPauseButton() {
                playPauseButton.innerText = isPlaying ? '⏸' : '▶';
            }

            function toggleRepeat() {
                isRepeating = !isRepeating;
                repeatButton.style.color = isRepeating ? 'green' : 'black'; // Optional: Change color for active state
            }

            function updateSeekbar() {
                const currentTime = audioPlayer.currentTime;
                const duration = audioPlayer.duration;

                seekbar.value = (currentTime / duration) * 100 || 0; // Handle NaN for undefined duration
                currentTimeDisplay.innerText = formatTime(currentTime);
                durationTimeDisplay.innerText = formatTime(duration || 0); // Handle NaN for undefined duration
            }

            function seekAudio(event) {
                const seekTo = (event.target.value / 100) * audioPlayer.duration;
                audioPlayer.currentTime = seekTo;
            }

            // Event listener for seekbar change
            seekbar.addEventListener('input', seekAudio);

            // Event listener for repeat
            audioPlayer.addEventListener('ended', function () {
                if (isRepeating) {
                    audioPlayer.currentTime = 0;
                    audioPlayer.play();
                }
            });

            // Event listener for play/pause button
            playPauseButton.addEventListener('click', togglePlayPause);

            // Event listener for repeat button
            repeatButton.addEventListener('click', toggleRepeat);

            // Volume control
            volumeControl.addEventListener('input', function () {
                audioPlayer.volume = volumeControl.value;
            });

            // Update seekbar and time display as song plays
            audioPlayer.addEventListener('timeupdate', updateSeekbar);

            // Load metadata to initialize duration
            audioPlayer.addEventListener('loadedmetadata', updateSeekbar);

            // Expose the playSong function to global scope
            window.playSong = playSong;
        });


        async function toggleLike(event, songId) {
            event.stopPropagation();

            try {
                const response = await fetch(`/songs/${songId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                });

                const data = await response.json();
                if (response.ok) {
                    // Update semua elemen dengan ID yang relevan
                    document.querySelectorAll(`#like-count-${songId}`).forEach((element) => {
                        element.innerText = `❤️ ${data.likes}`;
                    });
                    // Alert dihapus untuk like/unlike
                } else {
                    console.error('Error:', data.message); // Log error untuk debugging
                }
            } catch (error) {
                console.error('An error occurred while toggling like', error); // Log error untuk debugging
            }
        }

        document.getElementById('addSongForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Cegah pengiriman form default

            let valid = true;
            // Cek validasi file
            const imageFile = document.getElementById('image').files[0];
            const musicFile = document.getElementById('file').files[0];

            // Validasi file gambar
            if (imageFile && !['image/jpeg', 'image/png', 'image/jpg'].includes(imageFile.type)) {
                alert('Image must be a JPG, JPEG, or PNG file.');
                valid = false;
            }

            // Validasi file musik (hanya memeriksa ekstensi)
            if (musicFile) {
                const musicFileExtension = musicFile.name.split('.').pop().toLowerCase();
                if (musicFileExtension !== 'mp3') {
                    alert('Music file must be an MP3 file.');
                    valid = false;
                }
            }

            if (valid) {
                // Kirim data form
                this.submit();
            }
        });
    </script>
</body>
</html>
