<header class="bg-gray-900 text-white shadow">
    <div style ="height: 70px;background-color: #dfd0bd;"
        class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
        <!-- Logo -->
        <h1 class="text-xl font-bold">
            <a href="{{ url('/') }}" class="hover:text-gray-300 transition duration-200">My Meeting App</a>
        </h1>

        <!-- Navigation -->
        <div class="flex items-center">

        <nav class="absolute top-4 right-4 space-x-4">
            {{-- <a href="{{ url('/') }}"
               class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200">
                Home
            </a> --}}

            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/home') }}"
                       class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition duration-200">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition duration-200">
                        Login
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition duration-200">
                            Register
                        </a>
                    @endif
                @endauth
            @endif
        </nav>
    </div>
    </div>
</header>
