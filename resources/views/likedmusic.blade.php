<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Favorite Songs</title>
    <link rel="stylesheet" href="{{ asset('css/likedmusic.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    <div class="container">
        <div class="header">
            <img src="{{ asset('images/likedsongs.jpg') }}" alt="Playlist Cover" class="playlist-img">
            <div class="playlist-info">
                <h2>My Favorite Songs</h2>
                <p>{{ $likedSongs->count() }} songs, {{ gmdate('i:s', $likedSongs->sum('duration')) }}</p>
            </div>
        </div>

        @if($likedSongs->isEmpty())
            <p style="text-align: center; margin-top: 20px;">You have not liked any songs yet.</p>
        @else
            <div class="controls-pusat">
                <button class="play-button" id="playAllButton">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="5 3 19 12 5 21" fill="black"/>
                    </svg>
                </button>
            </div>

            <div class="track-list">
                <div class="track-headers">
                    <div>#</div>
                    <div>Title</div>
                    <div>Artist</div>
                    <div>Date added</div>
                    <div>Duration</div>
                </div>

                @foreach($likedSongs as $index => $song)
                    <div class="track"
                        data-file="{{ asset('storage/' . $song->file) }}"
                        data-title="{{ $song->title }}"
                        data-artist="{{ $song->artist }}"
                        data-duration="{{ $song->duration }}"
                        data-image="{{ asset('storage/' . $song->image) }}">
                        <div>{{ $index + 1 }}</div>
                        <div class="track-title">{{ $song->title }}</div>
                        <div>{{ $song->artist }}</div>
                        <div>{{ $song->pivot->created_at->diffForHumans() }}</div>
                        <div>{{ gmdate('i:s', $song->duration) }}</div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="music-player">
        <img id="currentSongImage" src="https://via.placeholder.com/100" alt="Song Image" class="song-image">
        <div id="title">
            <div id="currentSongTitle" class="song-title">No song selected</div>
            <div id="currentSongArtist" class="song-artist">-</div>
        </div>

        <div class="player-content">
            <div class="seekbar-container">
                <input type="range" id="seekbar" min="0" max="100" value="0" class="seekbar">
            </div>

            <div class="controls">
                <button class="shuffle-button" onclick="toggleShuffle()">🔀</button>
                <button id="prevButton">⏮</button>
                <button id="playPauseButton">⏯</button>
                <button id="nextButton">⏭</button>
                <button class="repeat-button" onclick="toggleRepeat()">🔁</button>
            </div>

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

    <audio id="audioPlayer" style="display: none;"></audio>

    <script src="{{ asset('js/likedmusic.js') }}"></script>
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
    </script>
</body>
</html>
