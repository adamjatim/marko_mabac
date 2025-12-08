@extends('layouts.app')

@section('title', 'Login Admin')

@section('content')
<div class="min-h-screen bg-linear-to-br from-blue-600 to-blue-800 flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-lg shadow-2xl p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">SPK Mobil MABAC</h1>
                <p class="text-gray-600">Login Admin</p>
            </div>

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <strong>Login Gagal!</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-800 mb-2">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="admin@example.com"
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-800 mb-2">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="••••••••"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition font-semibold"
                >
                    Login
                </button>
            </form>

            <div class="mt-8 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <p class="text-sm text-gray-700"><strong>Demo Akun:</strong></p>
                <p class="text-sm text-gray-600">Email: test@example.com</p>
                <p class="text-sm text-gray-600">Password: password</p>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    Kembali ke Halaman Utama
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
