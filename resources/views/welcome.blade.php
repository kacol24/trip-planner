<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Itinerary</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        td p {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
<div class="container-fluid p-0">
    <div class="table-responsive">
        <table class="table table-bordered border-dark">
            @foreach($itineraries as $itinerary)
                <tr class="table-secondary border-dark">
                    <th class="text-center">
                        {{ strtoupper($itinerary->date->format('d D')) }}
                    </th>
                </tr>
                <tr>
                    <td class="p-0">
                        <table class="table table-bordered m-0 border-dark">
                            <thead>
                            <tr>
                                <th></th>
                                <th class="text-center">
                                    {{ $itinerary->theme }}
                                </th>
                                <th class="text-end text-nowrap" style="width: 300px;">
                                    TOTAL: Rp{{ number_format($itinerary->total_for_the_day, 0, ',', '.') }}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($itinerary->notes)
                                <tr>
                                    <td></td>
                                    <td>
                                        <em class="font-sm">
                                            {!! $itinerary->notes !!}
                                        </em>
                                    </td>
                                    <td></td>
                                </tr>
                            @endif
                            <tr>
                                <td style="width: 300px;">
                                    AKOMODASI
                                </td>
                                <td style="width: 500px;">
                                    @if($itinerary->accomodation)
                                        <div class="fw-bold">
                                            {{ optional($itinerary->accomodation)->name }}
                                        </div>
                                    @else
                                        TBD
                                    @endif
                                </td>
                                <td class="text-end" style="width: 300px;">
                                    @if($itinerary->accomodation)
                                        @ Rp{{ number_format($itinerary->room_rate) }}
                                        x {{ $itinerary->room_count }}
                                        = <strong>Rp{{ number_format($itinerary->accomodation_cost) }}</strong>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 300px;">
                                    TRANSPORTASI
                                </td>
                                <td style="width: 500px;">
                                    @if($itinerary->transporation)
                                        <div class="fw-bold">
                                            {{ optional($itinerary->transporation)->name }}
                                        </div>
                                    @else
                                        TBD
                                    @endif
                                </td>
                                <td class="text-end" style="width: 300px;">
                                    @if($itinerary->transporation)
                                        Rp{{ number_format($itinerary->transporation_rate) }}
                                        + Rp{{ number_format($itinerary->fuel_cost) }} ({{ $itinerary->distance }}km)
                                        <strong>Rp{{ number_format($itinerary->transporation_cost) }}</strong>
                                    @endif
                                </td>
                            </tr>
                            @foreach($itinerary->schedules as $schedule)
                                <tr>
                                    <td style="width: 300px;">
                                        {!! $schedule->notes !!}
                                    </td>
                                    <td style="width: 500px">
                                        @if($schedule->destination->destination_type_id != App\Models\DestinationType::TYPE_OTW)
                                            {{ strtoupper($schedule->destination->destinationType->name) }}
                                        @endif
                                        <div @class(['fw-bold' => $schedule->destination->destination_type_id != App\Models\DestinationType::TYPE_OTW])>
                                            {{ $schedule->destination->name }}
                                        </div>
                                        <em class="font-sm">
                                            {!! $schedule->destination->notes !!}
                                        </em>
                                    </td>
                                    <td class="text-end" style="width: 300px;">
                                        @if($schedule->price_per_pax)
                                            @ Rp{{ number_format($schedule->price_per_pax, 0, ',', '.') }}
                                            x {{ $schedule->pax }}
                                        @endif
                                        @if($schedule->total_cost)
                                            = <strong>Rp{{ number_format($schedule->total_cost, 0, ',', '.') }}</strong>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
</body>
</html>
