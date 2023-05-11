<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Historical Data</title>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h1>Historical Data</h1>

    <table class="styled-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Open</th>
                <th>High</th>
                <th>Low</th>
                <th>Close</th>
                <th>Volume</th>
            </tr>
        </thead>
        <tbody id="dataRows">
            @if(session('historicalData'))
                @foreach(session('historicalData')['prices'] as $price)
                    <tr class="data-row">
                        <td>{{ date('Y-m-d H:s:i', $price['date']) ?? 'N/A' }}</td>
                        <td>{{ $price['open'] ?? 'N/A' }}</td>
                        <td>{{ $price['high'] ?? 'N/A' }}</td>
                        <td>{{ $price['low'] ?? 'N/A' }}</td>
                        <td>{{ $price['close'] ?? 'N/A' }}</td>
                        <td>{{ $price['volume'] ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6">No data available</td>
                </tr>
            @endif
        </tbody>
    </table>

    <button id="showMoreBtn">Show more</button>

    <canvas id="priceChart"></canvas>

    <script>
        window.historicalData = @json(session('historicalData')['prices'] ?? []);
    </script>
    <script src="{{ asset('js/historical.js') }}"></script>
</body>
</html>
