<?php

function normalizePhone($number)
{
    // only digits
    $num = preg_replace('/[^0-9]/', '', $number);

    // Convert leading 0 → 62
    if (substr($num, 0, 1) === '0') {
        return '62' . substr($num, 1);
    }

    // If already international
    if (substr($num, 0, 2) === '62') {
        return $num;
    }

    return $num;
}

function generatePickupCode()
{
    return rand(100000, 999999);
}
