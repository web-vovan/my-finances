<?php

if (!function_exists('priceFormat')) {
    function priceFormat(int $price) {
        return number_format($price, 0, '', ' ') . ' ₽';
    }
}

if (!function_exists('getMonthName')) {
    function getMonthName(int $month) {
        switch ($month) {
            case 1:
                return 'январь';
            case 2:
                return 'февраль';
            case 3:
                return 'март';
            case 4:
                return 'апрель';
            case 5:
                return 'май';
            case 6:
                return 'июнь';
            case 7:
                return 'июль';
            case 8:
                return 'август';
            case 9:
                return 'сентябрь';
            case 10:
                return 'октябрь';
            case 11:
                return 'ноябрь';
            case 12:
                return 'декабрь';
            default:
                return '';
        }
    }
}

if (!function_exists('percentRatio')) {
    /**
     * Функция вычисляет процентное соотношение чисел из массива
     *
     * @param array $data
     * @return mixed
     */
    function percentRatio(array $data): array {
        $sum = 0;

        foreach ($data as $item) {
            $sum += $item;
        }

        return array_reduce($data, function ($acc, $item) use ($sum) {
            $percent = $item * 100 / $sum;

            $acc[] = [
                'item' => $item,
                'percent' => intval(round($percent)),
            ];

            return $acc;
        }, []);
    }
}
