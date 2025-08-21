<?php

namespace App\Livewire;

use App\Adapters\VovanDB;
use Carbon\Carbon;
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

    public array $priceData;

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
        $rawData = VovanDB::select('SELECT date FROM costs ORDER BY date ASC');

        // Оставляем уникальные даты
        $rawData = array_reduce($rawData, function($acc, $item) {
            $year = (int) substr($item['date'], 0, 4);
            $month = (int) substr($item['date'], 5, 2);

            $acc[$year][$month] = $month;

            return $acc;
        }, []);

        foreach ($rawData as $key => $item) {
            $months = array_map(function($i) {
                return [
                    "month" => $i,
                    "monthName" => getMonthName($i)
                ];
            }, $item);

            $this->dateData[$key] = array_values($months);
        }
 
        return $this->dateData;
    }

    /**
     * Данные по расходам
     *
     * @return array
     */
    public function getPriceData(): array
    {
        // Категории
        $categories = array_reduce(VovanDB::select('SELECT * FROM categories'), function ($acc, $item) {
            $acc[$item['id']] = $item['name'];
            return $acc;
        }, []);

        $sql = "SELECT * FROM costs";

        // if (!$this->isFamily) {
            $sql .= ' WHERE user_id = ' . auth()->user()->id;
        // }

        $costs = VovanDB::select($sql);

        if ($this->isPeriod) {
            $costs = array_filter($costs, function($item) {
                if (
                    $item['date'] >= Carbon::create($this->startYearPeriod, $this->startMonthPeriod)
                    && $item['date'] <= Carbon::create($this->endYearPeriod, $this->endMonthPeriod)->endOfMonth()
                ) {
                    return true;
                }

                return false;
            });
        } else {
            $costs = array_filter($costs, function($item) {
                $year = (int) substr($item['date'], 0, 4);
                $month = (int) substr($item['date'], 5, 2);
                if ($year === $this->year && $month === $this->month) {
                    return true;
                }

                return false;
            });
        }

        // Группируем по категориям
        $data = array_reduce($costs, function($acc, $item) use($categories) {
            if (!isset($acc[$item['category_id']])) {
                $acc[$item['category_id']]['totalPrice'] = 0;
                $acc[$item['category_id']]['category'] = $categories[$item['category_id']];
            }

            $acc[$item['category_id']]['totalPrice'] += $item['price'];

            return $acc;
        }, []);

        // Сортируем
        usort($data, function ($a, $b) {
            return $b['totalPrice'] - $a['totalPrice'];
        });

        $percentRatio = collect(
            percentRatio(array_map(fn($item) => $item['totalPrice'], $data))
        );

        $this->totalPrice = array_reduce($data, function($acc, $item) {
            return $acc + $item['totalPrice'];
        }, 0);

        return array_map(function ($item) use ($percentRatio) {
           $item['percent'] =  $percentRatio
                ->where('item', $item['totalPrice'])
                ->value('percent');

            return $item;
        }, $data);
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
                'monthName' => getMonthName($this->month),
                'startMonthPeriodName' => getMonthName($this->startMonthPeriod),
                'endMonthPeriodName' => getMonthName($this->endMonthPeriod),
                'notValidPeriod' => Carbon::create($this->startYearPeriod, $this->startMonthPeriod) > Carbon::create($this->endYearPeriod, $this->endMonthPeriod)
            ]);
    }
}
