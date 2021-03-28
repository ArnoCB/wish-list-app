@extends('layouts.app')

@section('content')

    <div class="row mb-3">
        <div class="col-lg-12 margin-tb">
            <div class="text-center">
                <h1>my wishlist</h1>
            </div>
        </div>
    </div>

    <div>
        @if (isset($items))
            <div class="container">
                @foreach (array_chunk($items, 4) as $items_row)
                    <div class="row">
                        @foreach ($items_row as $item)
                            <div class="col-3" id="{!! $item->id !!}">
                                <div class="top-right">
                                    <form action="/wishlist/{!! $item->wishlist_id !!}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}

                                        <button>remove</button>
                                    </form>
                                </div>

                                <img src="{!! $item->image !!}"
                                     alt="{!! $item->api !!}" width="100%" style="max-height: 80%;">

                                <div class="bottom-left" style="max-width: 70%;"><b>{!! $item->name !!}</b></div>

                                <div class="bottom-right">{!! $item->price !!}</div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
