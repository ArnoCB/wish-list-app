@extends('layouts.app')

@section('styles')
<link rel="stylesheet"
          href="https://unpkg.com/@chrisoakman/chessboardjs@1.0.0/dist/chessboard-1.0.0.min.css"
          integrity="sha384-q94+BZtLrkL1/ohfjR8c6L+A6qzNH9R2hBLwyoAfu3i/WCvQjzL2RQJ3uNHDISdU"
          crossorigin="anonymous">
@endsection

@section('head_scripts')

    <script src="https://unpkg.com/@chrisoakman/chessboardjs@1.0.0/dist/chessboard-1.0.0.min.js"
            integrity="sha384-8Vi8VHwn3vjQ9eUHUxex3JSN/NFqUg3QbPyX8kWyb93+8AC/pPWTzj+nHtbC5bxD"
            crossorigin="anonymous"></script>
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-lg-12 margin-tb">
            <div class="text-center">
                <h1>solutions</h1>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm">
                <div id="myBoard" style="width:400px; "></div>
            </div>

            <div class="col-sm">
                <div class="row">
                    <div class="col">
                        <p>This problem has {!! count($fen_solutions) !!} solutions,
                            not taking rotation and reflection into account:</p>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col">
                        <label>
                            <select id="select_solution" class="form-control input-sm" style="width:auto;">
                                @foreach ($fen_solutions as $indexKey => $solution)
                                    <option value="{!! $solution[0] !!}">{{ $indexKey + 1}}. {{$solution[1]}}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <p>Under reflection in the d-file solutions 1-20 are the same as solutions 40-21</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                      Taking reflection and rotation into account, there are ca. 5 or 6 unique solutions.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    const board = Chessboard('myBoard');

    $( "#select_solution" ).change(function() {

        const fen_solution = $( "#select_solution" ).val();

        board.position(fen_solution);
    }).trigger('change');
</script>
@endsection



