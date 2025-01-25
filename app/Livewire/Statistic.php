<?php

namespace App\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Statistic extends Component
{
    public int $month;
    public int $year;

    public int $totalPrice;

    public ?int $startYearPeriod;
    public ?int $startMonthPeriod;
    
    public ?int $endYearPeriod;
    public ?int $endMonthPeriod;

    public array $dateData;
    public Collection $priceData;

    public $isFamily = false;
    public $isPeriod = false;

    public function mount()
    {
        $this->month = date('n');
        $this->year = date('Y');

        $this->dateData = $this->getDateData();
        $this->priceData = $this->getPriceData();

        if (!isset($this->dateData[$this->year])) {
            $this->dateData[$this->year] = [];
        }

        $this->startYearPeriod = array_key_first($this->dateData);
        $this->endYearPeriod = array_key_last($this->dateData);

        $this->startMonthPeriod = !is_null($this->startYearPeriod)
            ? $this->dateData[$this->startYearPeriod][0]['month']
            : null;

        $this->endMonthPeriod = !is_null($this->endYearPeriod)
            ? $this->dateData[$this->endYearPeriod][array_key_last($this->dateData[$this->endYearPeriod])]['month']
            : null;
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

        $builder = DB::table('costs as t1')
            ->select(DB::raw('SUM(t1.price) as totalPrice'), 't2.name')
            ->leftJoin('categories as t2', 't1.category_id', '=', 't2.id')
            ->whereIn('user_id', $users)
            ->groupBy('t2.name')
            ->orderByDesc('totalPrice');

        if ($this->isPeriod) {
            $builder
                ->where('date', '>=', Carbon::create($this->startYearPeriod, $this->startMonthPeriod))
                ->where('date', '<=', Carbon::create($this->endYearPeriod, $this->endMonthPeriod)->endOfMonth());
        } else {
            $builder
                ->whereMonth('date', $this->month)
                ->whereYear('date', $this->year);
        }

        $data = $builder->get();

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

    public function changePeriodYear()
    {
        if (!isset($this->dateData[$this->startYearPeriod][$this->startMonthPeriod])) {
            $this->startMonthPeriod = $this->dateData[$this->startYearPeriod][0]['month'];
        }

        if (!isset($this->dateData[$this->endYearPeriod][$this->endMonthPeriod])) {
            $this->startMonthPeriod = $this->dateData[$this->endYearPeriod][array_key_last($this->dateData[$this->endYearPeriod])]['month'];
        }

        $this->changeOption();
    }

    public function changeFamily()
    {
        $this->isFamily = !$this->isFamily;

        $this->changeOption();
    }

    public function changePeriod()
    {
        $this->isPeriod = !$this->isPeriod;

        $this->changeOption();
    }


    /**
     * Смена опции
     */
    public function changeOption()
    {
        $this->priceData = $this->getPriceData();
    }

    public function render()
    {
        return view('livewire.statistic')
            ->with([
                'priceData' => $this->priceData,
                'totalPrice' => $this->priceData->sum('totalPrice'),
                'labels' => json_encode($this->priceData->pluck('name')),
                'values' => json_encode($this->priceData->pluck('totalPrice')),
                'monthName' => getMonthName($this->month),
                'startMonthPeriodName' => getMonthName($this->startMonthPeriod),
                'endMonthPeriodName' => getMonthName($this->endMonthPeriod),
                'notValidPeriod' => Carbon::create($this->startYearPeriod, $this->startMonthPeriod) > Carbon::create($this->endYearPeriod, $this->endMonthPeriod)
            ]);
    }
}
