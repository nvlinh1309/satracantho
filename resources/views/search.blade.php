<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel</title>

    <!-- Fonts -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="{{ URL::asset('js/scripts.js') }}"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="container">
    <hr>
    <form method="post" action="{{ route('postSearch') }}">
        @csrf
        <div class="input-group">
            <input type="text" name="locale" id="search_keyword" value="{{ old('locale') }}" autocomplete="off" required class="form-control" placeholder="Enter your keyword...">
            <div class="input-group-btn">
                <button class="btn btn-default" type="submit">
                    Search
                </button>
            </div>
        </div>
    </form>
    <div id="keywordsList" style="position: absolute;">
    </div>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Locale</th>
            <th scope="col">Value</th>
            <th scope="col">Translation</th>
            <th scope="col" style="width: 50px"></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($translations))
        @foreach($translations as $tran)
            <tr>
                <th scope="row">{{ $tran->id }}</th>
                <td>{{ $tran->locale }}</td>
                <td>{{ $tran->value }}</td>
                <td>

                    <input type="hidden" value="{{ $tran->locale }}" class="locale{{ $tran->id }}">
                    <input type="text" class="form-control translations" data-id="{{ $tran->id }}" value="{!! $tran->translation !!}">
                </td>
                <td><span style="font-size: 30px;color: green; display: none" class="fa fa-check-circle-o save{{$tran->id}}"></span></td>
            </tr>
        @endforeach
        @else
            <tr>
                <th scope="row" colspan="5"><i style="color: red">Please enter keywords</i> </th>

            </tr>
        @endif
        </tbody>
    </table>
</div>
</body>
</html>
