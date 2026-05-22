<?php

namespace App\Helpers;

class QRCodeHelper
{
    public static function generate($text, $size = 200)
    {
        // Gunakan Google Charts API (gratis, tanpa install)
        $url = "https://chart.googleapis.com/chart?chs={$size}x{$size}&cht=qr&chl=" . urlencode($text);
        return $url;
    }
    
    public static function generateBarcode($text)
    {
        // Gunakan Barcode Generator API
        $url = "https://barcode.tec-it.com/barcode.ashx?data=" . urlencode($text) . "&code=Code128&dpi=96";
        return $url;
    }
}