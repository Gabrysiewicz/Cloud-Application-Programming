<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>New Site</title>
        <meta name="description" content="">
        <meta name="author" content="HeHexa6ty">

        <link rel='stylesheet' type="text/css" href="{{ asset('css/style.css') }}">
        <link rel='stylesheet' type="text/css" href="{{ asset('css/navbar.css') }}">
        <link rel='stylesheet' type="text/css" href="{{ asset('css/search.css') }}">
        <link rel='stylesheet' type="text/css" href="{{ asset('css/forms.css') }}">
        <link rel='stylesheet' type="text/css" href="{{ asset('css/offert.css') }}">
        <link rel='stylesheet' type="text/css" href="{{ asset('css/manage.css') }}">

    </head>
    <body>
        <nav>
            <a href='/'> <img src='{{ asset('images/no-image.jpg') }}' width='75px' height='75px' alt='logo'> </a>
            <ul>
                <li><a href='/?profession=barber'>Barber</a></li>
                <li><a href='/?profession=hairdresser'>Hairdresser</a></li>
                <li><a href='/?profession=cosmetic'>Cosmetic</a></li>
            </ul>
            <ul>
                <li><a href='/offerts/create'>Add offert</a></li>
                @auth
                    <li><a href='/offerts/manage'>Manage</a></li>
                    <li>
                        <form method="POST" action='/logout'>
                        @csrf
                        <button type='submit' class='ffd'>Sign out</button>
                        </form>
                    </li>
                @else
                    <li><a href='/register' class="">Sign up</a></li>
                    <li><a href='/login'>Sign in</a></li>
                @endauth
                
            </ul>
        </nav>
        <main>
            {{-- @yield('content') --}}
            {{$slot}}
        </main>
    </body>
</html>