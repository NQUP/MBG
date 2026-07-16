document.addEventListener('DOMContentLoaded', function() {
    var loginError = document.getElementById('loginError');
    if (loginError) {
        Swal.fire({
            icon: "error",
            title: "Login Gagal",
            text: loginError.textContent || "Username atau password salah.",
            confirmButtonColor: "#c0503c",
        });
    }
});
