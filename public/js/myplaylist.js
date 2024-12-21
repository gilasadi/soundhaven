document.addEventListener('DOMContentLoaded', function () {
    const audioPlayer = document.getElementById('audioPlayer');
    const currentSongImage = document.getElementById('currentSongImage');
    const currentSongTitle = document.getElementById('currentSongTitle');
    const currentSongArtist = document.getElementById('currentSongArtist');
    const prevButton = document.getElementById('prevButton');
    const nextButton = document.getElementById('nextButton');
    const playPauseButton = document.getElementById('playPauseButton');
    const shuffleButton = document.getElementById('shuffleButton');
    const repeatButton = document.getElementById('repeatButton');
    const seekBar = document.getElementById('seekBar');
    const currentTimeDisplay = document.getElementById('currentTime');
    const durationTimeDisplay = document.getElementById('durationTime');
    const volumeControl = document.getElementById('volumeControl');

    let isRepeatActive = false; // Repeat status
    let isShuffleActive = false; // Shuffle status
    let playlist = []; // Playlist data
    let currentIndex = -1; // Current song index
    let shuffleOrder = []; // Shuffle order
    let playedStack = []; // Played song stack
    let isSeeking = false; // Seekbar interaction status

    function formatTime(time) {
        const minutes = Math.floor(time / 60);
        const seconds = Math.floor(time % 60);
        return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    }

    // Update seekbar dan waktu saat audio diputar
    audioPlayer.addEventListener('timeupdate', () => {
        // Jangan update seekbar saat sedang disesuaikan oleh pengguna
        if (!isSeeking) {
            const currentTime = audioPlayer.currentTime || 0;
            const duration = audioPlayer.duration || 1; // Hindari pembagian dengan nol
            seekbar.value = (currentTime / duration) * 100;
            currentTimeDisplay.textContent = formatTime(currentTime);
            durationTimeDisplay.textContent = formatTime(duration);
            console.log('formatTime:', audioPlayer.currentTime);
        }
    });
    // Variabel untuk mengontrol jika seekbar sedang disesuaikan


    // Saat seekbar digeser, update waktu audioPlayer secara langsung
    seekbar.addEventListener('input', () => {
        isSeeking = true; // Tandai sedang menggeser seekbar
        const newTime = (seekbar.value / 100) * audioPlayer.duration
        audioPlayer.currentTime = newTime // Update waktu audioPlayer sesuai seekbar
        currentTimeDisplay.textContent = formatTime(newTime); // Update tampilan waktu
        console.log('Seekbar Input:', newTime);
        console.log('music time:', audioPlayer.currentTime);
    });

    // Ketika pengguna selesai menggeser seekbar, hentikan perubahan otomatis
    seekbar.addEventListener('change', () => {
        isSeeking = false; // Tandai selesai menggeser seekbar

    });


    // Function to play a song based on index
    function playSong(index) {
        if (index < 0 || index >= playlist.length) {
            console.error("Invalid song index:", index);
            return;
        }

        const song = playlist[index];
        audioPlayer.src = `/storage/${song.file}`;
        currentSongImage.src = song.image ? `/storage/${song.image}` : 'https://via.placeholder.com/100';
        currentSongTitle.textContent = song.title;
        currentSongArtist.textContent = song.artist;
        audioPlayer.play();

        currentIndex = index; // Update current index
        if (!playedStack.includes(index)) {
            playedStack.push(index); // Add to stack if not already present
        }
        updateControls();
    }

    // Update control buttons
    function updateControls() {
        prevButton.disabled = playedStack.length <= 1;
        nextButton.disabled = !isShuffleActive && currentIndex >= playlist.length - 1;
        playPauseButton.textContent = audioPlayer.paused ? '⏯' : '⏸';
    }

    // Handle Previous button
    prevButton.addEventListener('click', () => {
        if (playedStack.length > 1) {
            playedStack.pop(); // Remove the last index
            const previousIndex = playedStack[playedStack.length - 1]; // Get the previous index
            playSong(previousIndex);
        } else if (!isShuffleActive && currentIndex > 0) {
            playSong(currentIndex - 1);
        }
    });

    // Handle Next button
    nextButton.addEventListener('click', () => {
        if (isShuffleActive) {
            playNextShuffle();
        } else if (currentIndex < playlist.length - 1) {
            playSong(currentIndex + 1);
        }
    });

    // Toggle Repeat
    repeatButton.addEventListener('click', () => {
        isRepeatActive = !isRepeatActive;
        repeatButton.classList.toggle('active', isRepeatActive);
    });

    // Toggle Shuffle
    shuffleButton.addEventListener('click', () => {
        isShuffleActive = !isShuffleActive;
        shuffleButton.classList.toggle('active', isShuffleActive);
        if (isShuffleActive) {
            generateShuffleOrder();
        }
    });

    // Generate Shuffle Order
    function generateShuffleOrder() {
        shuffleOrder = Array.from({ length: playlist.length }, (_, i) => i);
        shuffleOrder = shuffleOrder.sort(() => Math.random() - 0.5);
        console.log('Shuffle Order:', shuffleOrder);
    }

    // Play next song in Shuffle mode
    function playNextShuffle() {
        if (shuffleOrder.length === 0) {
            generateShuffleOrder();
        }
        const nextIndex = shuffleOrder.shift();
        playedStack.push(nextIndex);
        playSong(nextIndex);
    }

    // Handle song end event
    audioPlayer.addEventListener('ended', () => {
        if (isRepeatActive) {
            audioPlayer.currentTime = 0;
            audioPlayer.play();
        } else if (isShuffleActive) {
            playNextShuffle();
        } else if (currentIndex < playlist.length - 1) {
            playSong(currentIndex + 1);
        }
    });

    // Handle Play Playlist button
    const playPlaylistButtons = document.querySelectorAll('.play-playlist');
    playPlaylistButtons.forEach(button => {
        button.addEventListener('click', function () {
            playlist = JSON.parse(this.getAttribute('data-playlist'));
            if (playlist.length > 0) {
                generateShuffleOrder();
                playedStack = [];
                playSong(0);
            } else {
                Swal.fire({
                    title: 'No Songs Found',
                    text: 'No songs available in this playlist.',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Handle Play/Pause button
    playPauseButton.addEventListener('click', () => {
        if (audioPlayer.paused) {
            audioPlayer.play();
        } else {
            audioPlayer.pause();
        }
        updateControls();
    });

    // Event listener untuk slider volume
    volumeControl.addEventListener('input', () => {
        audioPlayer.volume = volumeControl.value; // Mengatur volume audio
    });

});
