<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#989898">
    <meta name="msapplication-TileColor" content="#989898">
    <meta name="theme-color" content="#989898">

    <title>Trip Calendar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        a {
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 100%;
            overflow-x: hidden;
            display: inline-block;
        }
    </style>
</head>
<body>
<div class="container-fluid p-0">
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
                <td></td>
                <th class="text-center" colspan="{{ $itineraries->count() }}">TANGGAL</th>
            </tr>
            <tr>
                <td></td>
                @foreach($itineraries as $itinerary)
                    <th class="text-center">
                        {{ strtoupper($itinerary->date->format('d')) }}
                    </th>
                @endforeach
            </tr>
            <tr>
                <td></td>
                @foreach($itineraries as $itinerary)
                    <th class="text-center">
                        {{ strtoupper($itinerary->date->format('D')) }}
                    </th>
                @endforeach
            </tr>
            <tr>
                <th>AKOMODASI</th>
                @foreach($itineraries as $itinerary)
                    <td>
                        {{ optional($itinerary->accomodation)->name }}
                    </td>
                @endforeach
            </tr>
            <tr>
                <th>TRANSPORT</th>
                @foreach($itineraries as $itinerary)
                    <td>
                        {{ optional($itinerary->transportation)->name }}
                    </td>
                @endforeach
            </tr>
            <tr>
                <td></td>
                <th class="text-center" colspan="{{ $itineraries->count() }}">
                    BUDGET
                </th>
            </tr>
            <tr class="table-light">
                <th>
                    Hotel
                </th>
                @foreach($itineraries as $itinerary)
                    <td class="text-end text-nowrap">
                        <table class="w-100">
                            <tr>
                                <td>
                                    Rp{{ number_format($itinerary->room_rate, 0, ',', '.') }}
                                    x {{ $itinerary->room_count }}
                                </td>
                                <th class="text-end">
                                    = Rp{{ number_format($itinerary->accomodation_cost, 0, ',', '.') }}
                                </th>
                            </tr>
                        </table>
                    </td>
                @endforeach
            </tr>
            <tr class="table-light">
                <th class="text-nowrap">Transport + Fuel</th>
                @foreach($itineraries as $itinerary)
                    <td class="text-end text-nowrap">
                        <table class="w-100">
                            <tr>
                                <td>
                                    Rp{{ number_format($itinerary->transportation_rate, 0, ',', '.') }}<br>
                                    + Rp{{ number_format($itinerary->fuel_cost, 0, ',', '.') }}
                                    ({{ $itinerary->distance }}km)
                                </td>
                                <th class="text-end">
                                    = Rp{{ number_format($itinerary->transportation_cost, 0, ',', '.') }}
                                </th>
                            </tr>
                        </table>
                    </td>
                @endforeach
            </tr>
            <tr class="table-light">
                <th>Meals</th>
                @foreach($itineraries as $itinerary)
                    <td class="text-end">
                        Rp{{ number_format($itinerary->kuliner_cost, 0, ',', '.') }}
                    </td>
                @endforeach
            </tr>
            <tr class="table-light">
                <th>Wisata</th>
                @foreach($itineraries as $itinerary)
                    <td class="text-end">
                        Rp{{ number_format($itinerary->wisata_cost, 0, ',', '.') }}
                    </td>
                @endforeach
            </tr>
            <tr class="table-secondary">
                <th class="text-nowrap">
                    TOTAL: Rp{{ number_format($itineraries->sum('total_for_the_day'), 0, ',', '.') }}
                </th>
                @foreach($itineraries as $itinerary)
                    <th class="text-end">
                        Rp{{ number_format($itinerary->total_for_the_day, 0, ',', '.') }}
                    </th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            <tr>
                <td></td>
                <th class="text-center" colspan="{{ $itineraries->count() }}">ACARA</th>
            </tr>
            <tr>
                <th>THEME</th>
                @foreach($itineraries as $itinerary)
                    <td class="fw-bold">
                        {{ $itinerary->theme }}
                    </td>
                @endforeach
            </tr>
            <tr>
                <td>NOTES</td>
                @foreach($itineraries as $itinerary)
                    <td style="width: 150px;max-width: 150px; word-break: break-all">
                        {!! $itinerary->notes !!}
                    </td>
                @endforeach
            </tr>
            <tr>
                <td></td>
                @foreach($itineraries as $itinerary)
                    <td class="p-0" style="width: 150px">
                        <table class="w-100 table table-bordered table-sm m-0">
                            @foreach($itinerary->schedules as $schedule)
                                @continue($schedule->destination->destination_type_id == App\Models\DestinationType::TYPE_OTW)
                                <tr @class([
                                        'border-bottom-0' => $schedule->total_cost || $schedule->last,
                                        'table-success' => $schedule->time_of_day == '10-morning',
                                        'table-info' => $schedule->time_of_day == '20-afternoon',
                                        'table-warning' => $schedule->time_of_day == '30-evening',
                                        'table-primary' => $schedule->time_of_day == '40-night',
                                    ])
                                >
                                    <td colspan="2"
                                        style="height: {{ $schedule->total_cost ? 'auto' : '66px' }}"
                                        @class([
                                            'border-bottom-0',
                                            'fw-bold' => $schedule->destination->destination_type_id == App\Models\DestinationType::TYPE_KULINER,
                                        ])
                                    >
                                        {{ $schedule->destination->name }}
                                    </td>
                                </tr>
                                @if($schedule->total_cost)
                                    <tr @class([
                                            'table-success' => $schedule->time_of_day == '10-morning',
                                            'table-info' => $schedule->time_of_day == '20-afternoon',
                                            'table-warning' => $schedule->time_of_day == '30-evening',
                                            'table-primary' => $schedule->time_of_day == '40-night',
                                        ])
                                        style="border-top-color: transparent !important">
                                        <td class="text-end"
                                            style="border-top-color: transparent !important; border-right-color: transparent !important">
                                            Rp{{ number_format($schedule->price_per_pax, 0, ',', '.') }}
                                            x {{ $schedule->pax }}
                                        </td>
                                        <td class="text-end"
                                            style="border-top-color: transparent !important; border-left-color: transparent !important">
                                            Rp{{ number_format($schedule->total_cost, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    </td>
                @endforeach
            </tr>
            </tbody>
        </table>
    </div>
</div>
<script>
    var anchors = document.getElementsByTagName('a');
    Array.from(anchors).forEach(function(e) {
        e.target = '_blank';
    });
</script>
</body>
</html>
