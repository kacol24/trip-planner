@if($transporation)
    <table style="text-align: left">
        <tr>
            <th colspan="2">
                {{ $transporation->name }}
            </th>
        </tr>
        <tr>
            <td>Rate</td>
            <td style="text-align: right;">
                Rp{{ number_format($itinerary->transportation_rate, 0, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td>
                Fuel Cost ({{ $itinerary->distance }} Km)
            </td>
            <td style="text-align: right;">
                Rp{{ number_format($itinerary->fuel_cost, 0, ',', '.') }}
            </td>
        </tr>
        <tr>
            <th>Total</th>
            <th style="text-align: right;">
                Rp{{ number_format($itinerary->transporation_cost, 0, ',', '.') }}
            </th>
        </tr>
    </table>

@endif
