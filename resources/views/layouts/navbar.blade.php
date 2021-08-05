<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Goods and Shoes Shop</a>

    <button class="navbar-toggler" type="button" data-toggle="collapse"
            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item {{ (request()->is('shop')) ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('shop') }}">Shop</a>
            </li>

            <li class="nav-item {{ (request()->is('wishlist')) ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('wishlist') }}">Wishlist
                    <span id="wishlist-count" class="badge badge-primary" style="vertical-align: top"></span>
                </a>
            </li>

            <li class="nav-item {{ (request()->is('queens_problem')) ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('queens') }}">7 Queens Problem</a>
            </li>
        </ul>
    </div>
</nav>

<script>
    $( document ).ready(function() {
        $.ajax({
            type: "GET",
            url: "/wishlist_count/",
            data: '',
            success: function (response) {

                const json = JSON.parse(response);

                $('#wishlist-count').html(json.wishlisted_number);
            }
        });
    });
</script>
