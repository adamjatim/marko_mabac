<nav class="bg-blue-600 text-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
        <div class="flex items-center gap-8">

            <a href="{{ Auth::check() ? route('admin.dashboard') : route('home') }}" class="text-2xl font-bold">SPK Mobil MABAC</a>
            <div class="flex gap-6">
                <a href="{{ route('mobil.index') }}" class="hover:text-blue-100">Daftar Mobil</a>
                <a href="{{ route('perhitungan.index') }}" class="hover:text-blue-100">Perhitungan</a>
                <a href="{{ route('kriteria.index') }}" class="hover:text-blue-100">Kriteria</a>
            </div>
        </div>
        <div>
            @auth
                <span class="mr-4">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded">Login Admin</a>
            @endauth
        </div>
    </div>
</nav>
