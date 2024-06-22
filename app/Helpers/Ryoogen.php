<?php

/**
 * Mengubah format menjadi mata uang rupiah (IDR)
 *
 * @param  int  $uang  angka yang ingin diubah
 * @return string Rp. $uang ,-
 */
if (!function_exists('money_format_idr')) {
    function money_format_idr($money, $withRp = true, $desimal = false)
    {
        $money = (float) $money;

        return $withRp
            ? 'Rp. ' . number_format($money, $desimal ? 2 : 0, ',', '.') . ''
            : number_format($money, $desimal ? 2 : 0, ',', '.');
    }
}
