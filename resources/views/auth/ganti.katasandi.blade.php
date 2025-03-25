@if (session('status'))
    <div>{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('kata_sandi.email') }}">
    @csrf
    <label>Email:</label>
    <input type="email" name="email" required>
    @error('email') <span>{{ $message }}</span> @enderror
    <button type="submit">Kirim Link Reset Password</button>
</form>
