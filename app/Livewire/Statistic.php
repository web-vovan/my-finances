<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Statistic extends Component
{
    public $month;
    public $year;
    public array $dateArr;

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
        $data = DB::table('costs as t1')
            ->select(DB::raw('SUM(t1.price) as totalPrice'), 't2.name')
            ->leftJoin('categories as t2', 't1.category_id', '=', 't2.id')
            ->whereMonth('date', '=', $this->month)
            ->whereYear('date', '=', $this->year)
            ->where('user_id', '=' ,auth()->user()->id)
            ->groupBy('t2.name')
            ->orderByDesc('totalPrice')
            ->get();

        return view('livewire.statistic')
            ->with([
                'data' => $data,
                'totalPrice' => $data->sum('totalPrice'),
                'labels' => json_encode($data->pluck('name')),
                'values' => json_encode($data->pluck('totalPrice')),
                'monthName' => getMonthName($this->month),
            ]);
    }
}
