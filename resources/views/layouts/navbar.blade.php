<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Goods and Shoes Shop</a>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item {{ (request()->is('shop')) ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('shop') }}">Shop</a>
            </li>

            <li class="nav-item {{ (request()->is('wishlist')) ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('wishlist') }}">Wishlist</a>
            </li>

            <li class="nav-item {{ (request()->is('queens_problem')) ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('queens') }}">Queens</a>
            </li>
        </ul>
    </div>
</nav>
