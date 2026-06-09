<?php

function toast($type, $message)
{
    $_SESSION[$type] = $message;
}

function formatHarga($harga)
{
    if ($harga >= 1000000) {
        return 'Rp' . round($harga / 1000000, 1) . 'M';
    }

    if ($harga >= 1000) {
        return 'Rp' . round($harga / 1000) . 'K';
    }

    return 'Rp' . $harga;
}

function waktuPesanan($datetime)
{
    $timestamp = strtotime($datetime);
    $selisih = time() - $timestamp;

    if ($selisih < 60) {
        $relative = "Baru saja";
    } elseif ($selisih < 3600) {
        $relative = floor($selisih / 60) . " menit yang lalu";
    } elseif ($selisih < 86400) {
        $relative = floor($selisih / 3600) . " jam yang lalu";
    } elseif ($selisih < 604800) {
        $relative = floor($selisih / 86400) . " hari yang lalu";
    } else {
        $relative = date('d M Y', $timestamp);
    }

    return [
        'relative' => $relative,
        'full' => date('d M Y • H:i', $timestamp)
    ];
}

?>