<?php

namespace App\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Statistic extends Component
{
    public int $month;
    public int $year;
    public array $monthData;
    public string $currentDateToString;

    public function mount()
    {
        $this->month = date('m');
        $this->year = date('Y');

        $this->currentDateToString = $this->year . '|' . $this->month;

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
            $date = Carbon::createFromFormat('Y m', "$item->year $item->month");
            $item->monthName = $date->monthName;
            $item->dateToString = $date->year . '|' . $date->month;
        });

        $this->monthData = $data->toArray();
    }

    public function render()
    {
        $data = DB::table('costs as t1')
            ->select(DB::raw('SUM(t1.price) as totalPrice'), 't2.name')
            ->leftJoin('categories as t2', 't1.category_id', '=', 't2.id')
            ->whereMonth('date', '=', $this->month)
            ->groupBy('t2.name')
            ->orderBy('totalPrice')
            ->get();

        return view('livewire.statistic')
            ->with([
                'labels' => json_encode($data->pluck('name')),
                'data' => json_encode($data->pluck('totalPrice'))
            ]);
    }
}
