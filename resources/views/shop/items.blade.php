@extends('layouts.app')

@section('content')

<div class="row mb-3">
    <div class="col-lg-12 margin-tb">
        <div class="text-center">
            <h1>shop</h1>
        </div>
    </div>
</div>

<div>
    @if (isset($items))
        <div class="container">
            @foreach (array_chunk($items, 4) as $items_row)
                <div class="row">
                    @foreach ($items_row as $item)
                        <div class="col-3 shop-item-box" id="{!! $item->id !!}">
                            <div class="top-right">
                                <form name="{!! $item->api !!}_{!! $item->id !!}">
                                    {{ csrf_field() }}

                                    <label>
                                        <input type="checkbox"
                                               class="form-checkbox"
                                               name="{!! $item->api !!}_{!! $item->id !!}"
                                               {{$item->wished ? 'checked' : ''}}>

                                    </label>
                                </form>
                            </div>

                            <img src="{!! $item->image !!}"
                                 alt="{!! $item->api !!}" style="max-width: 100%; max-height: 80%;">

                            <div class="bottom-left" style="max-width: 65%;">
                                <b>{!! $item->name !!}</b>
                                <br>
                                @if ($item->description)
                                    <small>
                                        {{ \Illuminate\Support\Str::limit($item->description, 60, $end='...') }}
                                    </small>
                                @endif
                            </div>

                            <div class="bottom-right">
                                {!! $item->price !!}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@section('script')
<script type="text/javascript">
    $("form").on("change", function (e) {
        const dataString = $(this).serialize();
        const name = e.target.name;

        $.ajax({
            type: "POST",
            url: "/wishlist/" + name,
            data: dataString,
            success: function (response) {

                const json = JSON.parse(response);

                $('#wishlist-count').html(json.wishlisted_number);
            }
        });

        e.preventDefault();
    });
</script>
@endsection
