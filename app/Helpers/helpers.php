<?php

if (!function_exists('priceFormat')) {
    function priceFormat(int $price) {
        return number_format($price, 0, '', ' ') . ' ₽';
    }
}

if (!function_exists('percentRatio')) {
    /**
     * Функция вычисляет процентное соотношение чисел из массива
     *
     * @param array $data
     */
    function percentRatio(array $data) {

    }
}
