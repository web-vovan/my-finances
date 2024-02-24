<div>
    <div class="row mt-4">
        <div class="col s6">
            <select wire:model="year" wire:change="changeYear" class="browser-default">
                @foreach ($dateArr as $key => $item)
                    <option value="{{ $key }}">{{ $key }}</option>
                @endforeach
            </select>
        </div>
        <div class="col s6">
            <select wire:model.live="month" wire:key="{{ $year }}" class="browser-default">
                @foreach ($dateArr[$year] as $key => $item)
                    <option value="{{ $item['month'] }}">{{ $item['monthName'] }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <canvas id="myChart" class="mb-4"></canvas>

    <table class="striped">
        <thead>
            <tr class="font-weight-bold">
                <td>Расходы за {{ $monthName }}:</td>
                <td>{{ priceFormat($totalPrice) }}</td>
            </tr>
        </thead>

        @foreach($data as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ priceFormat((int) $item->totalPrice) }}</td>
            </tr>
        @endforeach
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: JSON.parse('{!! $labels !!}'),
            datasets: [{
                data: JSON.parse('{!! $values !!}'),
                borderWidth: 1
            }]
        },
    });
</script>
