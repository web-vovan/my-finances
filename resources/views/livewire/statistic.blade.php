<div>
    <h4 class="mt-3 mb-3">Статистика</h2>
    <div class="flex align-items-center justify-content-between">
        <div class="flex">
            <div class="mr-4">
                <select wire:model="year" wire:change="changeYear" class="browser-default">
                    @foreach ($dateData as $key => $item)
                        <option value="{{ $key }}">{{ $key }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select wire:model="month" wire:change="changeOption" wire:key="{{ $year }}" class="browser-default">
                    @foreach ($dateData[$year] as $key => $item)
                        <option value="{{ $item['month'] }}">{{ $item['monthName'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex">
            <i
                @class([
                    'material-icons',
                    'prefix',
                    'small',
                    'mr-4',
                    'teal-text' => $isPeriod,
                    'grey-text' => !$isPeriod,
                ])
                wire:click="changePeriod"
                wire:model="isPeriod"
            >today</i>
            <i
                @class([
                    'material-icons',
                    'prefix',
                    'small',
                    'teal-text' => $isFamily,
                    'grey-text' => !$isFamily,
                ])
                wire:click="changeFamily"
                wire:model="isFamily"
            >people</i>
        </div>
    </div>

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
