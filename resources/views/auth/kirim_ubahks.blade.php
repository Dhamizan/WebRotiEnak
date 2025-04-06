<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Lupa Kata Sandi</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100 p-4">

  <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-1">Lupa Kata Sandi</h2>
    <p class="text-sm text-center text-gray-500 mb-6">Masukkan email Anda untuk mendapatkan tautan reset kata sandi</p>

    <!-- Form Masukkan Email -->
    <form>
      <!-- Email -->
      <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" placeholder="Masukkan email Anda" class="w-full mt-1 p-3 border border-gray-300 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-yellow-400">
      </div>

      <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-3 rounded-lg transition-all">
        Kirim Tautan Reset
      </button>
    </form>
  </div>

</body>
</html>
