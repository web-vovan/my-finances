<?php

namespace App\Livewire;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Statistic extends Component
{
    public $month;
    public $year;

    public array $dateData;
    public Collection $priceData;

    public $isFamily = false;

    public function mount()
    {
        $this->month = date('n');
        $this->year = date('Y');

        $this->dateData = $this->getDateData();
        $this->priceData = $this->getPriceData();
    }

    /**
     * Данные по годам и месяцам
     *
     * @return array
     */
    public function getDateData(): array
    {
        $data = DB::table('costs')
            ->select([
                DB::raw('YEAR(date) as year'),
                DB::raw('MONTH(date) as month')
            ])
            ->distinct()
            ->orderBy(DB::raw('YEAR(date)'))
            ->orderBy(DB::raw('MONTH(date)'))
            ->get();

        $data->map(function($item) {
            $item->monthName = getMonthName($item->month);
        });

        return $data->reduce(function($acc, $item) {
            $acc[$item->year][] = [
                'month' => $item->month,
                'monthName' => $item->monthName,
            ];

            return $acc;
        }, []);
    }

    /**
     * Данные по расходам
     *
     * @return Collection
     */
    public function getPriceData(): Collection
    {
        $users = $this->isFamily
            ? DB::table('users')
                ->select('id')
                ->where('family_id', '=', auth()->user()->family_id)
                ->get()
                ->pluck('id')
                ->toArray()
            : [auth()->user()->id];

        $data = DB::table('costs as t1')
            ->select(DB::raw('SUM(t1.price) as totalPrice'), 't2.name')
            ->leftJoin('categories as t2', 't1.category_id', '=', 't2.id')
            ->whereMonth('date', '=', $this->month)
            ->whereYear('date', '=', $this->year)
            ->whereIn('user_id', $users)
            ->groupBy('t2.name')
            ->orderByDesc('totalPrice')
            ->get();

        $percentRatio = collect(
            percentRatio(
                $data
                    ->map(fn ($item) => (int) $item->totalPrice)
                    ->toArray()
            )
        );

        $this->totalPrice = $data->sum('totalPrice');

        return $data->each(function($item) use ($percentRatio) {
            $item->percent = $percentRatio
                ->where('item', $item->totalPrice)
                ->value('percent');
        });
    }

    public function changeYear()
    {
        $this->month = $this->dateData[$this->year][0]['month'];

        $this->changeOption();
    }

    /**
     * Смена опции
     */
    public function changeOption()
    {
        $this->priceData = $this->getPriceData();

        $this->dispatch(
            'chartUpdate',
            labels: json_encode($this->priceData->pluck('name')),
            data: json_encode($this->priceData->pluck('totalPrice'))
        );
    }

    public function render()
    {
        return view('livewire.statistic')
            ->with([
                'priceData' => $this->priceData,
                'totalPrice' => $this->priceData->sum('totalPrice'),
                'labels' => json_encode($this->priceData->pluck('name')),
                'values' => json_encode($this->priceData->pluck('totalPrice')),
                'monthName' => getMonthName($this->month)
            ]);
    }
}
