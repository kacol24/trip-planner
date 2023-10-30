@if($accomodation)
    <table style="text-align: left">
        <tr>
            <th>
                {{ $accomodation->name }}
            </th>
            <th>
                - {{ $accomodation->area->name }}
            </th>
        </tr>
        <tr>
            <td>Rate</td>
            <td style="text-align: right;">
                Rp{{ number_format($itinerary->room_rate, 0, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td>
                Rooms
            </td>
            <td style="text-align: right;">
                {{ $itinerary->room_count }}
            </td>
        </tr>
        <tr>
            <th>Total</th>
            <th style="text-align: right;">
                Rp{{ number_format($itinerary->accomodation_cost, 0, ',', '.') }}
            </th>
        </tr>
    </table>

@endif
