<div>
    <h4 class="mt-3 mb-3">Статистика</h2>
    <div class="flex align-items-center justify-content-between">
        @if ($isPeriod)
            <div class="flex-direction-column">
                <div class="grey-text">начало периода:</div>
                <div class="flex mb-2">
                    <div class="mr-4">
                        <select wire:model="startYearPeriod" wire:change="changePeriodYear" class="browser-default">
                            @foreach ($dateData as $key => $item)
                                <option value="{{ $key }}">{{ $key }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select wire:model="startMonthPeriod" wire:change="changeOption" wire:key="{{ $startYearPeriod }}" class="browser-default">
                            @foreach ($dateData[$startYearPeriod] as $key => $item)
                                <option value="{{ $item['month'] }}">{{ $item['monthName'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grey-text">конец периода:</div>
                <div class="flex">
                    <div class="mr-4">
                        <select wire:model="endYearPeriod" wire:change="changePeriodYear" class="browser-default">
                            @foreach ($dateData as $key => $item)
                                <option value="{{ $key }}">{{ $key }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select wire:model="endMonthPeriod" wire:change="changeOption" wire:key="{{ $endYearPeriod }}" class="browser-default">
                            @foreach ($dateData[$endYearPeriod] as $key => $item)
                                <option value="{{ $item['month'] }}">{{ $item['monthName'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @if ($notValidPeriod)
                    <div class="red-text">период указан неверно</div>
                @endif
            </div>
        @else
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
        @endif
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
                @if ($isPeriod)
                    <td>Расходы за <br> {{ $startMonthPeriodName }} {{ $startYearPeriod }} - {{ $endMonthPeriodName }} {{ $endYearPeriod }}:</td>
                @else
                    <td>Расходы за {{ $monthName }} {{ $year }}:</td>
                @endif
                <td>{{ priceFormat($totalPrice) }}</br>
                    <div class="grey-text">{{ priceFormat($totalPrice2) }}</div></td>
                <td>100%</td>
            </tr>
        </thead>

        @foreach($priceData as $key => $item)
            <tr>
                <td>{{ $item->name }}
                    <div class="grey-text">{{ $this->priceData2[$key]['category'] }}</div>
                </td>
                <td>{{ priceFormat((int) $item->totalPrice) }}
                    <div class="grey-text">{{ priceFormat($this->priceData2[$key]['totalPrice']) }}</div>
                </td>
                <td>{{ $item->percent }}%
                    <div class="grey-text">{{ $this->priceData2[$key]['percent'] }}%</div>
                </td>
            </tr>
        @endforeach
    </table>
</div>
