<?php


namespace App\Library;


class Utility
{
    const orderStatus = [
        1 => 'Создана',
        2 => 'Одобрена',
        3 => 'В работе',
        4 => 'Доставлена',
        5 => 'К доработке',
    ];

    public static function getOrderStatus($status) {
        return self::orderStatus[$status];
    }

    public static function changeDecimalSeparator($number) {
        return  floatval(str_replace(',', '.', str_replace('.', '', $number)));
    }
}
