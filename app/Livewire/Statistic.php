<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Statistic extends Component
{
    public $month;
    public $year;
    public array $dateArr;
    public $isFamily = false;

    public function mount()
    {
        $this->month = date('n');
        $this->year = date('Y');

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

        $this->dateArr = $data->reduce(function($acc, $item) {
            $acc[$item->year][] = [
                'month' => $item->month,
                'monthName' => $item->monthName,
            ];

            return $acc;
        }, []);
    }

    public function changeYear()
    {
        $this->month = $this->dateArr[$this->year][0]['month'];
    }

    public function render()
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

        $data->each(function($item) use ($percentRatio) {
           $item->percent = $percentRatio
               ->where('item', $item->totalPrice)
               ->value('percent');
        });

        return view('livewire.statistic')
            ->with([
                'data' => $data,
                'totalPrice' => $data->sum('totalPrice'),
                'labels' => json_encode($data->pluck('name')),
                'values' => json_encode($data->pluck('totalPrice')),
                'monthName' => getMonthName($this->month)
            ]);
    }
}
