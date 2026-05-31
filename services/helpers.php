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

?>