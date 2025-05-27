<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <p>Halo, {{ $name }}</p>
    <p>Klik link berikut untuk mengubah password Anda:</p>
    <a href="{{ $resetLink }}" style="display:inline-block;padding:10px 20px;color:white;background:#28a745;text-decoration:none;border-radius:5px;">Ubah Kata Sandi/</a>
    <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
</body>
</html>
