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

    <title>{{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        a {
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 100%;
            overflow-x: hidden;
            /*display: inline-block;*/
        }

        td p {
            margin-bottom: 0;
        }

        table, tr, td, th {
            page-break-inside: avoid;
        }

        @media print {
            .padding-print {
                padding: 1cm;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid p-0 padding-print">
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
                @if(request()->has('with_budget'))
                    <th class="text-end text-nowrap" style="width: 25%;">
                        TOTAL: Rp{{ number_format($itinerary->total_for_the_day, 0, ',', '.') }}
                    </th>
                @endif
            </tr>
            @if($itinerary->accomodation)
                <tr class="{{ $loop->even ? 'table-secondary border-dark' : '' }}">
                    <td style="width: 25%;">
                        AKOMODASI
                    </td>
                    <td style="width: 50%;">
                        <div class="fw-bold">
                            {{ $itinerary->accomodation->name }}
                        </div>
                        @if($itinerary->accomodation->notes)
                            <small class="text-muted">
                                {!! $itinerary->accomodation->notes !!}
                            </small>
                        @endif
                    </td>
                    @if(request()->has('with_budget') && $itinerary->accomodation_cost)
                        <td class="text-end" style="width: 25%;">
                            @ Rp{{ number_format($itinerary->room_rate, 0, ',', '.') }}
                            x {{ $itinerary->room_count }}
                            = <strong>Rp{{ number_format($itinerary->accomodation_cost, 0, ',', '.') }}</strong>
                        </td>
                    @endif
                </tr>
            @endif
            @if($itinerary->transportation)
                <tr class="{{ $loop->even ? 'table-secondary border-dark' : '' }}">
                    <td style="width: 25%;">
                        TRANSPORTASI
                    </td>
                    <td style="width: 50%;">
                        <div class="fw-bold">
                            {{ $itinerary->transportation->name }}
                        </div>
                        @if($itinerary->distance)
                            <small class="text-muted">
                                ({{ $itinerary->distance }}km)
                            </small>
                        @endif
                    </td>
                    @if(request()->has('with_budget') && $itinerary->transportation_cost)
                        <td class="text-end" style="width: 25%;">
                            Rp{{ number_format($itinerary->transportation_rate, 0, ',', '.') }}
                            + Rp{{ number_format($itinerary->fuel_cost, 0, ',', '.') }} ({{ $itinerary->distance }}
                            km)
                            = <strong>Rp{{ number_format($itinerary->transportation_cost, 0, ',', '.') }}</strong>
                        </td>
                    @endif
                </tr>
            @endif
            @if($itinerary->notes)
                <tr class="{{ $loop->even ? 'table-secondary border-dark' : '' }}">
                    <td></td>
                    <td>
                        <em class="font-sm">
                            {!! $itinerary->notes !!}
                        </em>
                    </td>
                    @if(request()->has('with_budget'))
                        <td></td>
                    @endif
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
                        @if($schedule->destination->notes)
                            <em class="ms-3 d-inline-block text-muted" style="word-break: break-all;">
                                {!! $schedule->destination->notes !!}
                            </em>
                        @endif
                    </td>
                    @if(request()->has('with_budget'))
                        <td class="text-end" style="width: 25%;">
                            @if($schedule->price_per_pax)
                                @ Rp{{ number_format($schedule->price_per_pax, 0, ',', '.') }}
                                x {{ $schedule->pax }}
                            @endif
                            @if($schedule->total_cost)
                                = <strong>Rp{{ number_format($schedule->total_cost, 0, ',', '.') }}</strong>
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach
        @endforeach
    </table>
</div>
<script>
    var anchors = document.getElementsByTagName('a');
    Array.from(anchors).forEach(function(e) {
        e.target = '_blank';
    });
</script>
</body>
</html>
