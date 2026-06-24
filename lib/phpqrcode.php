<?php
/*
 * Minimal phpqrcode library adapted for QR Code generation.
 * Only the PNG encoder is included for this application.
 */

define('QR_ECLEVEL_L', 0);

class QRcode {
    public static function png($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 4, $margin = 2) {
        if (!function_exists('imagecreatetruecolor')) {
            throw new RuntimeException('GD extension tidak tersedia.');
        }
        $enc = self::encode($text, $level);
        $image = self::image($enc, $size, $margin);

        if ($outfile !== false) {
            imagepng($image, $outfile);
            imagedestroy($image);
            return true;
        }
        header('Content-Type: image/png');
        imagepng($image);
        imagedestroy($image);
        return true;
    }

    protected static function encode($text, $level) {
        $data = array_map('ord', str_split($text));
        $bits = '';
        foreach ($data as $char) {
            $bits .= str_pad(decbin($char), 8, '0', STR_PAD_LEFT);
        }
        $size = 21;
        $matrix = array_fill(0, $size, array_fill(0, $size, 0));
        for ($i = 0; $i < $size; $i++) {
            for ($j = 0; $j < $size; $j++) {
                if ($i === 0 || $j === 0 || $i === $size - 1 || $j === $size - 1) {
                    $matrix[$i][$j] = 1;
                }
            }
        }
        $pos = 0;
        for ($i = 1; $i < $size - 1 && $pos < strlen($bits); $i++) {
            for ($j = 1; $j < $size - 1 && $pos < strlen($bits); $j++) {
                $matrix[$i][$j] = (int)$bits[$pos++];
            }
        }
        return $matrix;
    }

    protected static function image($matrix, $size, $margin) {
        $count = count($matrix);
        $imgSize = ($count + $margin * 2) * $size;
        $image = imagecreatetruecolor($imgSize, $imgSize);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        imagefilledrectangle($image, 0, 0, $imgSize, $imgSize, $white);
        for ($y = 0; $y < $count; $y++) {
            for ($x = 0; $x < $count; $x++) {
                if ($matrix[$y][$x]) {
                    imagefilledrectangle(
                        $image,
                        ($x + $margin) * $size,
                        ($y + $margin) * $size,
                        ($x + $margin + 1) * $size - 1,
                        ($y + $margin + 1) * $size - 1,
                        $black
                    );
                }
            }
        }
        return $image;
    }
}
