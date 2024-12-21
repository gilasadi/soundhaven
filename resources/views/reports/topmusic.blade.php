<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Top Music</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <h1>WMP</h1>

    <h2>Top 5 Music Report</h2>

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Artist</th>
                <th>Likes</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($topSongs as $song)
                <tr>
                    <td>{{ $song->title }}</td>
                    <td>{{ $song->artist }}</td>
                    <td>{{ $song->likes_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tampilkan Playlist Hanya untuk User -->
    @if ($user && $user->role === 'user')
        <h2>Your Playlists</h2>
        @if ($playlists->isEmpty())
            <p>You have no playlists.</p>
        @else
            @foreach ($playlists as $playlist)
                <h3>{{ $playlist->name }}</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Artist</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($playlist->songs as $song)
                            <tr>
                                <td>{{ $song->title }}</td>
                                <td>{{ $song->artist }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        @endif
    @endif
</body>
</html>
