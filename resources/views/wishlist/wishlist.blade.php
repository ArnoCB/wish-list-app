@extends('layouts.app')

@section('content')

    <div class="row mb-3">
        <div class="col-lg-12 margin-tb">
            <div class="text-center">
                <h1>wishlist</h1>
            </div>
        </div>
    </div>

    <div>
        <div class="container">
            @if (isset($items) && count($items) > 0)
                @foreach (array_chunk($items, 4) as $items_row)
                    <div class="row">
                        @foreach ($items_row as $item)
                            <div class="col-3 shop-item-box" id="{!! $item->id !!}">
                                <div class="top-right">
                                    <form action="/wishlist/{!! $item->wishlist_id !!}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}

                                        <button class="btn btn-light">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                 fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>

                                <img src="{!! $item->image !!}"
                                     alt="{!! $item->api !!}" style="max-width: 100%; max-height: 80%;">

                                <div class="bottom-left" style="max-width: 70%;">
                                    <b>{!! $item->name !!}</b>

                                    <br>

                                    @if ($item->description)
                                        <small>
                                            {{ \Illuminate\Support\Str::limit($item->description, 60, $end='...') }}
                                        </small>
                                    @endif
                                </div>

                                <div class="bottom-right">{!! $item->price !!}</div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @else
                <div class="row">
                    <div class="col">
                      <h2 style="text-align:center; color: gray;">Your wishlist is empty</h2>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
