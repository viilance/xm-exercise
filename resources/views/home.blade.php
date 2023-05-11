<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>XM test</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h1>Welcome to the XM Exercise</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="container mt-5">
        <form action="/submit" method="post">
            @csrf

            <div class="form-group">
                <label for="company_symbol">Company Symbol</label>
                <select class="{{ $errors->has('company_symbol') ? 'error' : '' }}" name="company_symbol" id="company_symbol">
                    <option value="">Select a company</option>
                    @foreach($companies as $symbol => $name)
                        <option value="{{$symbol}}" {{ old('company_symbol') == $symbol ? 'selected' : '' }}>
                            {{$symbol}}
                        </option>
                    @endforeach
                </select>

                <!-- Error -->
                @if ($errors->has('company_symbol'))
                    <div class="error">
                        {{ $errors->first('company_symbol') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input
                    type="text"
                    class="form-control {{ $errors->has('start_date') ? 'error' : '' }}"
                    name="start_date"
                    id="start_date"
                    placeholder="YYYY-mm-dd"
                    value="{{ old('start_date') }}"
                >

                <!-- Error -->
                @if ($errors->has('start_date'))
                    <div class="error">
                        {{ $errors->first('start_date') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="end_date">End Date</label>
                <input
                    type="text"
                    class="form-control {{ $errors->has('end_date') ? 'error' : '' }}"
                    name="end_date"
                    id="end_date"
                    placeholder="YYYY-mm-dd"
                    value="{{ old('end_date') }}"
                >

                <!-- Error -->
                @if ($errors->has('end_date'))
                    <div class="error">
                        {{ $errors->first('end_date') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    class="form-control {{ $errors->has('email') ? 'error' : '' }}"
                    name="email"
                    id="email"
                    value="{{ old('email') }}"
                >

                <!-- Error -->
                @if ($errors->has('email'))
                    <div class="error">
                        {{ $errors->first('email') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-dark btn-block" value="Submit">
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    <script src="{{ asset('js/home.js') }}"></script>
</body>
</html>
