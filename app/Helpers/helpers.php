<?php

if (!function_exists('priceFormat')) {
    function priceFormat(int $price) {
        return number_format($price, 0, '', ' ') . ' ₽';
    }
}
