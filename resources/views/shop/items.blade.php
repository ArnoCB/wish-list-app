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
                                <form action="/wishlist/{!! $item->api !!}_{!! $item->id !!}"
                                      method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}

                                    <label>
                                        <input type="checkbox" name="{!! $item->api !!}_{!! $item->id !!}"
                                               {{$item->wished ? 'checked' : ''}} onchange="this.form.submit();">
                                    </label>
                                </form>
                            </div>

                            <img src="{!! $item->image !!}"
                                 alt="{!! $item->api !!}" style="max-width: 100%; max-height: 80%;">

                            <div class="bottom-left" style="max-width: 65%;"><b>{!! $item->name !!}</b></div>

                            <div class="bottom-right">{!! $item->price !!}</div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection

