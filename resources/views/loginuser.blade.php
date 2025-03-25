<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/YOUR_KIT_CODE.js" crossorigin="anonymous"></script> 
</head>
<body class="flex items-center justify-center min-h-screen bg-white">

    <div class="bg-white rounded-lg shadow-lg w-[900px] flex overflow-hidden">
        <!-- Bagian Kiri (Form Login) -->
        <div class="w-1/2 flex items-center justify-center p-10">
            <div class="w-full">
                <h1 class="text-3xl font-bold text-black text-center">Welcome Back</h1>
                <p class="text-gray-600 text-sm text-center">Please enter your account</p>

                <!-- Input Username -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700">Username</label>
                    <div class="relative mt-1">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input type="text" placeholder="Enter Your Username . . ." 
                            class="w-full pl-10 p-3 border border-gray-300 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                </div>

                <!-- Input Password -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="relative mt-1">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" placeholder="Enter Your Password . . ." 
                            class="w-full pl-10 p-3 border border-gray-300 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                </div>

                <!-- Tombol Login -->
                <button class="mt-6 w-full bg-yellow-400 text-white font-bold py-3 rounded-lg shadow hover:bg-yellow-500">
                    Login
                </button>
            </div>
        </div>

        <!-- Bagian Kanan (Gambar) -->
        <div class="w-1/2 relative">
            <img src="images/logo login.png" class="w-full h-full object-cover rounded-r-lg">
        </div>
    </div>

</body>
</html>
