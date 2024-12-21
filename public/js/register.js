function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);

    if (input.type === 'password') {
        input.type = 'text';
        button.textContent = '👁️‍🗨️'; // Ikon saat terlihat
        button.classList.add('active'); // Tambahkan class aktif
    } else {
        input.type = 'password';
        button.textContent = '👁️'; // Ikon saat disembunyikan
        button.classList.remove('active'); // Hapus class aktif
    }
}
