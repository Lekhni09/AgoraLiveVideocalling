<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Meeting</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gray-100 font-sans flex">

    <!-- Left Side Background Section -->

    <div class="relative w-1/2 hidden md:flex items-center justify-center">
        <!-- Portrait Background -->
        <img src="{{ url('/images/team-working-together-project.jpg')}}" alt="Portrait Background"
             class="absolute inset-0 w-full h-full object-cover z-0">

        <!-- Overlapping Text -->
        <div class="relative z-10 bg-white bg-opacity-75 p-6 rounded-lg shadow-lg max-w-sm text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Stay Connected</h2>
            <p class="text-gray-600">Effortlessly create or join meetings with just a click. Stay connected with your team and collaborate seamlessly. Experience secure and reliable video conferencing anytime, anywhere.</p>
        </div>
    </div>

    <!-- Right Side Content -->
    <div class="w-full md:w-1/2 flex flex-col justify-center items-center relative bg-white p-6 md:p-12">
        <!-- Navbar -->
        <nav class="absolute top-4 right-4">
            <li class="relative list-none">
                <a id="navbarDropdown" href="#" role="button"
                    class="bg-gray-100 px-4 py-2 rounded-lg shadow text-black inline-block">
                    {{ Auth::user()->name }}
                </a>
                <div id="dropdownMenu"
                    class="absolute right-0 mt-2 w-44 bg-white border border-gray-200 rounded-lg shadow-lg hidden z-50">
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">{{ __('Logout') }}</a>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </li>
        </nav>

        <!-- Meeting Card -->
        <div class="w-full max-w-md bg-white p-6 rounded-xl shadow-xl z-10">
            <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Meeting Room</h2>

            <!-- Meeting Link Input -->
            <input type="text" id="linkUrl"
                class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                placeholder="Enter or generate a meeting link">

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row justify-between mt-6 space-y-3 sm:space-y-0 sm:space-x-4">
                @if (Auth::user())
                    <a href="{{ url('createMeeting') }}"
                        class="w-full sm:w-1/2 text-center bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition flex items-center justify-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14m-6 0h6m-6 0a2 2 0 01-2-2m2 2a2 2 0 002-2m-2 2V8m0 4a2 2 0 00-2-2m2 2a2 2 0 002-2m-2 2V8" />
                        </svg>
                        <span>Create Meeting</span>
                    </a>
                @endif

                <button id="join-btn2" onclick="joinUserMeeting()"
                    class="w-full sm:w-1/2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition flex items-center justify-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                    <span>Join Meeting</span>
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        function joinUserMeeting() {
            var link = $('#linkUrl').val().trim();
            if (link === "") {
                alert("Please enter a meeting link!");
            } else {
                window.location.href = link;
            }
        }

        document.getElementById('navbarDropdown').addEventListener('click', function (event) {
            event.preventDefault();
            var dropdown = document.getElementById('dropdownMenu');
            dropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', function (event) {
            var dropdown = document.getElementById('dropdownMenu');
            if (!event.target.closest('#navbarDropdown')) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</body>

</html>
