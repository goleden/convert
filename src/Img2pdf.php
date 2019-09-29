<?php

/**
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 *
 */

namespace goleden\convert;

/**
 * Img2pdf
 *
 */
class Img2pdf
{
    /**
     * Img2pdf
     *
     * @param array $images 图片路径
     * @param string $pdf pdf文件路径
     *
     * @return string
     */
    public function convert(array $images, string $pdf)
    {
        if (!extension_loaded('Imagick')) {
            throw new \Exception('Imagick extension not found!');
        }
        if (is_file($pdf)) {
            unlink($pdf);
        }
        $anim = new \Imagick($images);
        $result = $anim->writeImages($pdf, true);
        if ($result != true) {
            throw new \Exception('Convert fail!');
        }
        return $pdf;
    }
}
