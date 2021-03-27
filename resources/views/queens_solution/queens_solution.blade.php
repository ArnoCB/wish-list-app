<html>
<head>
    <link rel="stylesheet"
          href="https://unpkg.com/@chrisoakman/chessboardjs@1.0.0/dist/chessboard-1.0.0.min.css"
          integrity="sha384-q94+BZtLrkL1/ohfjR8c6L+A6qzNH9R2hBLwyoAfu3i/WCvQjzL2RQJ3uNHDISdU"
          crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
            integrity="sha384-ZvpUoO/+PpLXR1lu4jmpXWu80pZlYUAfxl5NsBMWOEPSjUn/6Z/hRTt8+pR6L4N2"
            crossorigin="anonymous"></script>

    <script src="https://unpkg.com/@chrisoakman/chessboardjs@1.0.0/dist/chessboard-1.0.0.min.js"
            integrity="sha384-8Vi8VHwn3vjQ9eUHUxex3JSN/NFqUg3QbPyX8kWyb93+8AC/pPWTzj+nHtbC5bxD"
            crossorigin="anonymous"></script>
</head>
<body>
<div id="myBoard" style="width: 400px"></div>

<select id="select_solution">
    @foreach ($fen_solutions as $solution)
        <option value="{!! $solution !!}">{{$solution}}</option>
    @endforeach
</select>

<script>
    const board = Chessboard('myBoard');

    $( "#select_solution" ).change(function() {

        const fen_solution = $( "#select_solution" ).val();

        board.position(fen_solution);
    }).trigger('change');
</script>
</body>
</html>


