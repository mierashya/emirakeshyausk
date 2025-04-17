<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Bank Mini</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(to right, #a7f3d0, #6ee7b7);
        }
        .glass {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen font-sans">

    <div class="w-full max-w-md p-8 rounded-2xl glass">
        <div class="mb-6 text-center">
            <h2 class="text-3xl font-extrabold text-green-700">Login</h2>
        </div>
        
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <div class="relative">
                    <input type="email" id="email" name="email" required
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
                        placeholder="email@domain.com">
                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-green-500">
                        <!-- Email Icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 12H8m0 0l4-4m0 4l-4 4m4-4v12" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
                        placeholder="••••••••">
                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-green-500">
                        <!-- Lock Icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 15v2m0 0v2m0-2h-2m2 0h2m-6 0a6 6 0 0112 0v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4a6 6 0 0112 0" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Remember me -->
            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="remember" class="form-checkbox text-green-600 rounded">
                    <span class="text-gray-600">Ingat saya</span>
                </label>
                <a href="#" class="text-green-600 hover:underline">Lupa Password?</a>
            </div>

            <!-- Button -->
            <button type="submit"
                class="w-full py-2 px-4 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition duration-200">
                Login
            </button>
        </form>

        <!-- Footer -->
        <p class="text-center text-sm text-gray-600 mt-6">
            Belum punya akun?
            <a href="#" class="text-green-600 hover:underline">Daftar sekarang</a>
        </p>
    </div>

</body>
</html>
