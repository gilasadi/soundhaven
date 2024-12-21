<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<link rel="stylesheet" href="{{ asset('css/fitures.css') }}">
<body>
    <header class="header">
        <h1>Welcome to SOUNDHAVEN</h1>
    </header>

    <nav class="nav">
        <ul>
            <li><a href="{{ route('dashboard') }}">Home</a></li>
            <li><a href="#add-song">Add Song</a></li>
            <li><a href="#play-music">Play Music</a></li>
            <li><a href="#create-playlist">Create Playlist</a></li>
            <li><a href="#liked-songs">Liked Songs</a></li>
            <li><a href="#update-profile">Update Profile</a></li>
        </ul>
    </nav>

    <main class="main-content">
        <section id="add-song" class="feature">
            <h2>Add Song</h2>
            <p>Upload your favorite songs to your personal library and enjoy them anytime.</p>
            <button>Add Song</button>
        </section>

        <section id="play-music" class="feature">
            <h2>Play Music</h2>
            <p>Stream your favorite tracks seamlessly with our intuitive player.</p>
            <button>Play Now</button>
        </section>

        <section id="create-playlist" class="feature">
            <h2>Create Playlist</h2>
            <p>Organize your music by creating custom playlists for any mood or occasion.</p>
            <button>Create Playlist</button>
        </section>

        <section id="liked-songs" class="feature">
            <h2>Liked Songs</h2>
            <p>Access all your liked songs in one place and enjoy them anytime.</p>
            <button>View Liked Songs</button>
        </section>

        <section id="update-profile" class="feature">
            <h2>Update Profile</h2>
            <p>Personalize your profile to match your style and music preferences.</p>
            <button>Update Profile</button>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; 2024 MusicHub. All rights reserved.</p>
    </footer>
</body>
</html>
