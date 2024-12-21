// Variabel global
const audioPlayer = document.getElementById('audioPlayer');
const playPauseButton = document.getElementById('playPauseButton');
const seekbar = document.getElementById('seekbar');
const volumeControl = document.getElementById('volumeControl');
const currentSongTitle = document.getElementById('currentSongTitle');
const currentSongArtist = document.getElementById('currentSongArtist');
const currentSongImage = document.getElementById('currentSongImage');
const currentTimeDisplay = document.getElementById('currentTime');
const durationTimeDisplay = document.getElementById('durationTime');

let currentTrackIndex = 0;
let isPlaying = false;
let isShuffle = false;
let isRepeat = false;

const tracks = Array.from(document.querySelectorAll('.track'));

// Fungsi utilitas
function formatTime(seconds) {
    const minutes = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${minutes}:${secs < 10 ? '0' : ''}${secs}`;
}

function updateSeekbar() {
    if (audioPlayer.duration) {
        seekbar.value = (audioPlayer.currentTime / audioPlayer.duration) * 100;
        currentTimeDisplay.textContent = formatTime(audioPlayer.currentTime);
        durationTimeDisplay.textContent = formatTime(audioPlayer.duration);
    }
}

function loadTrack(index) {
    const track = tracks[index];
    audioPlayer.src = track.dataset.file;
    currentSongTitle.textContent = track.dataset.title;
    currentSongArtist.textContent = track.dataset.artist;
    currentSongImage.src = track.dataset.image;
    audioPlayer.load();
}

function playTrack() {
    audioPlayer.play();
    isPlaying = true;
    playPauseButton.textContent = '⏸';
}

function pauseTrack() {
    audioPlayer.pause();
    isPlaying = false;
    playPauseButton.textContent = '⏯';
}

function nextTrack() {
    currentTrackIndex = isShuffle ? Math.floor(Math.random() * tracks.length) : (currentTrackIndex + 1) % tracks.length;
    loadTrack(currentTrackIndex);
    playTrack();
}

function prevTrack() {
    currentTrackIndex = currentTrackIndex > 0 ? currentTrackIndex - 1 : tracks.length - 1;
    loadTrack(currentTrackIndex);
    playTrack();
}

function toggleShuffle() {
    isShuffle = !isShuffle;
    document.querySelector('.shuffle-button').classList.toggle('active', isShuffle);
}

function toggleRepeat() {
    isRepeat = !isRepeat;
    document.querySelector('.repeat-button').classList.toggle('active', isRepeat);
}

// Event Listener
playPauseButton.addEventListener('click', () => {
    isPlaying ? pauseTrack() : playTrack();
});

document.getElementById('nextButton').addEventListener('click', nextTrack);
document.getElementById('prevButton').addEventListener('click', prevTrack);

seekbar.addEventListener('input', () => {
    audioPlayer.currentTime = (seekbar.value / 100) * audioPlayer.duration;
});

audioPlayer.addEventListener('timeupdate', updateSeekbar);
audioPlayer.addEventListener('ended', () => {
    if (isRepeat) {
        playTrack();
    } else {
        nextTrack();
    }
});

volumeControl.addEventListener('input', () => {
    audioPlayer.volume = volumeControl.value;
});

tracks.forEach((track, index) => {
    track.addEventListener('click', () => {
        currentTrackIndex = index;
        loadTrack(currentTrackIndex);
        playTrack();
    });
});

// Muat trek pertama saat halaman dimuat
if (tracks.length > 0) {
    loadTrack(currentTrackIndex);
}

// Tambahkan di bagian Event Listener
document.getElementById('playAllButton').addEventListener('click', () => {
    if (tracks.length > 0) {
        currentTrackIndex = 0; // Setel ke lagu pertama
        loadTrack(currentTrackIndex); // Muat lagu pertama
        playTrack(); // Mainkan lagu
    } else {
        console.warn('Tidak ada lagu yang tersedia untuk diputar.');
    }
});
