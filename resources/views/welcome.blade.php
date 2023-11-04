<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bali Trip Itinerary 23 DES 2023 - 02 JAN 2024</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        td p {
            margin-bottom: 0;
        }

        table, tr, td, th {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
<div class="container-fluid pt-3">
    <table class="table w-100 m-0 table-bordered border-dark">
        @foreach($itineraries as $itinerary)
            <tr class="{{ $loop->even ? 'table-secondary border-dark' : '' }}">
                <th class="text-center" colspan="3">
                    {{ strtoupper($itinerary->date->format('d D')) }}
                </th>
            </tr>
            <tr class="{{ $loop->even ? 'table-secondary border-dark' : '' }}">
                <th></th>
                <th class="text-center">
                    {{ $itinerary->theme }}
                </th>
                <th class="text-end text-nowrap" style="width: 25%;">
                    TOTAL: Rp{{ number_format($itinerary->total_for_the_day, 0, ',', '.') }}
                </th>
            </tr>
            <tr class="{{ $loop->even ? 'table-secondary border-dark' : '' }}">
                <td style="width: 25%;">
                    AKOMODASI
                </td>
                <td style="width: 50%;">
                    @if($itinerary->accomodation)
                        <div class="fw-bold">
                            {{ optional($itinerary->accomodation)->name }}
                        </div>
                    @else
                        TBD
                    @endif
                </td>
                <td class="text-end" style="width: 25%;">
                    @if($itinerary->accomodation)
                        @ Rp{{ number_format($itinerary->room_rate) }}
                        x {{ $itinerary->room_count }}
                        = <strong>Rp{{ number_format($itinerary->accomodation_cost) }}</strong>
                    @endif
                </td>
            </tr>
            <tr class="{{ $loop->even ? 'table-secondary border-dark' : '' }}">
                <td style="width: 25%;">
                    TRANSPORTASI
                </td>
                <td style="width: 50%;">
                    @if($itinerary->transportation)
                        <div class="fw-bold">
                            {{ optional($itinerary->transportation)->name }}
                        </div>
                    @else
                        TBD
                    @endif
                    @if($itinerary->distance)
                        <small class="text-muted">
                            ({{ $itinerary->distance }}km)
                        </small>
                    @endif
                </td>
                <td class="text-end" style="width: 25%;">
                    @if($itinerary->transportation)
                        Rp{{ number_format($itinerary->transportation_rate) }}
                        + Rp{{ number_format($itinerary->fuel_cost) }} ({{ $itinerary->distance }}km)
                        <strong>Rp{{ number_format($itinerary->transportation_cost) }}</strong>
                    @endif
                </td>
            </tr>
            @if($itinerary->notes)
                <tr class="{{ $loop->even ? 'table-secondary border-dark' : '' }}">
                    <td></td>
                    <td>
                        <em class="font-sm">
                            {!! $itinerary->notes !!}
                        </em>
                    </td>
                    <td></td>
                </tr>
            @endif
            @foreach($itinerary->schedules as $schedule)
                <tr class="{{ $loop->parent->even ? 'table-secondary border-dark' : '' }}">
                    <td style="width: 25%;">
                        {!! $schedule->notes !!}
                    </td>
                    <td style="width: 50%;">
                        @if($schedule->destination->destination_type_id != App\Models\DestinationType::TYPE_OTW)
                            {{ strtoupper($schedule->destination->destinationType->name) }}
                        @endif
                        <div @class(['fw-bold' => $schedule->destination->destination_type_id != App\Models\DestinationType::TYPE_OTW])>
                            {{ $schedule->destination->name }}
                        </div>
                        <em class="ms-3 d-inline-block text-muted" style="word-break: break-all;">
                            {!! $schedule->destination->notes !!}
                        </em>
                    </td>
                    <td class="text-end" style="width: 25%;">
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
        @endforeach
    </table>
</div>
</body>
</html>
