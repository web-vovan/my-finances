<div>
    <div class="row mt-4 flex align-items-center">
        <div class="col s3">
            <select wire:model="year" wire:change="changeYear" class="browser-default">
                @foreach ($dateData as $key => $item)
                    <option value="{{ $key }}">{{ $key }}</option>
                @endforeach
            </select>
        </div>
        <div class="col s4">
            <select wire:model="month" wire:change="changeOption" wire:key="{{ $year }}" class="browser-default">
                @foreach ($dateData[$year] as $key => $item)
                    <option value="{{ $item['month'] }}">{{ $item['monthName'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="col s5">
            <div class="switch">
                <label>
                    <input type="checkbox" wire:model="isFamily" wire:change="changeOption">
                    <span class="lever"></span>
                    @if ($isFamily)
                        <span>семья</span>
                    @else
                        <span>мое</span>
                    @endif
                </label>

            </div>
        </div>
    </div>

    <canvas id="myChart" class="mb-4"></canvas>

    <table class="striped">
        <thead>
            <tr class="font-weight-bold">
                <td>Расходы за {{ $monthName }}:</td>
                <td>{{ priceFormat($totalPrice) }}</td>
                <td>100%</td>
            </tr>
        </thead>

        @foreach($priceData as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ priceFormat((int) $item->totalPrice) }}</td>
                <td>{{ $item->percent }}%</td>
            </tr>
        @endforeach
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@script
    <script>
        const ctx = document.getElementById('myChart');

        const chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: JSON.parse('{!! $labels !!}'),
                datasets: [{
                    data: JSON.parse('{!! $values !!}'),
                    borderWidth: 1
                }]
            },
        });

        $wire.on('chartUpdate', (event) => {
            chart.data.labels = JSON.parse(event.labels)
            chart.data.datasets[0].data = JSON.parse(event.data)
            chart.update()
        });
    </script>
@endscript
