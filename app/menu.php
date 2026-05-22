<?php

declare(strict_types=1);

function menu_items(): array
{
    return [
        ['id' => 'soto_semarang_kecil', 'category' => 'Menu Utama', 'name' => 'Soto Semarang Porsi Kecil', 'price' => 10000, 'type' => 'food'],
        ['id' => 'soto_semarang_sedang', 'category' => 'Menu Utama', 'name' => 'Soto Semarang Sedang', 'price' => 13000, 'type' => 'food'],
        ['id' => 'soto_semarang_besar', 'category' => 'Menu Utama', 'name' => 'Soto Semarang Besar', 'price' => 15000, 'type' => 'food'],
        ['id' => 'soto_semarang_bungkus', 'category' => 'Menu Utama', 'name' => 'Soto Semarang Bungkus', 'price' => 15000, 'type' => 'food'],
        ['id' => 'soto_kwali_kecil', 'category' => 'Menu Utama', 'name' => 'Soto Kwali Porsi Kecil', 'price' => 13000, 'type' => 'food'],
        ['id' => 'soto_kwali_sedang', 'category' => 'Menu Utama', 'name' => 'Soto Kwali Sedang', 'price' => 16000, 'type' => 'food'],
        ['id' => 'soto_kwali_besar', 'category' => 'Menu Utama', 'name' => 'Soto Kwali Besar', 'price' => 19000, 'type' => 'food'],
        ['id' => 'soto_kwali_bungkus', 'category' => 'Menu Utama', 'name' => 'Soto Kwali Bungkus', 'price' => 19000, 'type' => 'food'],
        ['id' => 'tempe_goreng', 'category' => 'Gorengan', 'name' => 'Tempe Goreng', 'price' => 1000, 'type' => 'addon'],
        ['id' => 'tahu_goreng', 'category' => 'Gorengan', 'name' => 'Tahu Goreng', 'price' => 1000, 'type' => 'addon'],
        ['id' => 'tempe_bacem', 'category' => 'Gorengan', 'name' => 'Tempe Bacem', 'price' => 2000, 'type' => 'addon'],
        ['id' => 'tahu_bacem', 'category' => 'Gorengan', 'name' => 'Tahu Bacem', 'price' => 2000, 'type' => 'addon'],
        ['id' => 'bergedel_kentang', 'category' => 'Gorengan', 'name' => 'Bergedel Kentang', 'price' => 3000, 'type' => 'addon'],
        ['id' => 'kerupuk', 'category' => 'Gorengan', 'name' => 'Kerupuk', 'price' => 1000, 'type' => 'addon'],
        ['id' => 'peyek', 'category' => 'Gorengan', 'name' => 'Peyek', 'price' => 5000, 'type' => 'addon'],
        ['id' => 'sate_usus', 'category' => 'Sate', 'name' => 'Sate Usus', 'price' => 4000, 'type' => 'addon'],
        ['id' => 'sate_telur_puyuh', 'category' => 'Sate', 'name' => 'Sate Telur Puyuh', 'price' => 4000, 'type' => 'addon'],
        ['id' => 'sate_kerang', 'category' => 'Sate', 'name' => 'Sate Kerang', 'price' => 5000, 'type' => 'addon'],
        ['id' => 'sate_ayam', 'category' => 'Sate', 'name' => 'Sate Ayam', 'price' => 7000, 'type' => 'addon'],
        ['id' => 'hati_ayam', 'category' => 'Sate', 'name' => 'Hati Ayam', 'price' => 7000, 'type' => 'addon'],
        ['id' => 'sayap', 'category' => 'Sate', 'name' => 'Sayap', 'price' => 8000, 'type' => 'addon'],
        ['id' => 'teh_oriental', 'category' => 'Minuman', 'name' => 'Teh Oriental', 'price' => 3000, 'type' => 'drink'],
        ['id' => 'teh_kampul', 'category' => 'Minuman', 'name' => 'Teh Kampul', 'price' => 5000, 'type' => 'drink'],
        ['id' => 'teh_tawar', 'category' => 'Minuman', 'name' => 'Teh Tawar', 'price' => 2000, 'type' => 'drink'],
        ['id' => 'jeruk', 'category' => 'Minuman', 'name' => 'Jeruk', 'price' => 6000, 'type' => 'drink'],
        ['id' => 'jeruk_tawar', 'category' => 'Minuman', 'name' => 'Jeruk Tawar', 'price' => 4000, 'type' => 'drink'],
        ['id' => 'air_putih', 'category' => 'Minuman', 'name' => 'Air Putih', 'price' => 1000, 'type' => 'drink'],
        ['id' => 'air_putih_dingin_hangat', 'category' => 'Minuman', 'name' => 'Air Putih Dingin / Hangat', 'price' => 2000, 'type' => 'drink'],
        ['id' => 'air_mineral_botol', 'category' => 'Minuman', 'name' => 'Air Mineral Botol', 'price' => 5000, 'type' => 'drink'],
    ];
}

function menu_lookup(): array
{
    $lookup = [];

    foreach (menu_items() as $item) {
        $lookup[$item['id']] = $item;
    }

    return $lookup;
}

function rupiah(int|float $amount): string
{
    return 'Rp ' . number_format((float) $amount, 0, ',', '.');
}
