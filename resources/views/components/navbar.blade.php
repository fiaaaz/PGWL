<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <i class="fa-solid fa-building-shield"></i> {{ $title }}
        </a>


        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>


        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('map') }}">
                        <i class="fa-solid fa-map-location-dot"></i> Map
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="fa-solid fa-house-chimney"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('table') }}">
                        <i class="fa-solid fa-table"></i> Table
                    </a>
                </li>

                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('api.points') }}" target="_blank">
                            <i class="fa-solid fa-database"></i> Data Point
                        </a>
                    </li>

                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="nav-link text-danger" type="submit">
                                <i class="fa-solid fa-right-from-bracket"></i>Logout
                            </button>
                        </form>
                    </li>
                @endauth

                @guest
                    <li class="nav-item">
                        <a class="nav-link text-primary" href="{{ route('login') }}">
                            <i class="fa-solid fa-right-to-bracket"></i> Login
                        </a>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
