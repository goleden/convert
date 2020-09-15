<?php

/**
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 *
 */

namespace goleden\convert;

/**
 * Pdf2img
 *
 */
class Pdf2img
{
    /**
     * Pdf2img
     *
     * @param string $pdf pdf文件路径
     * @param string $dir 保存目录
     * @param string $resoulution 分辨率
     * @param string $compression 压缩
     * @param string $format 格式
     *
     * @return array
     */
    public function convert($pdf, $dir, $resoulution = '120', $compression = '100', $format = 'png')
    {
        if (!extension_loaded('Imagick')) {
            throw new \Exception('Imagick extension not found!');
        }
        if (!is_file($pdf)) {
            throw new \Exception('Pdf not found!');
        }
        $im = new \Imagick();
        $im->setResolution($resoulution, $resoulution);
        $im->setCompressionQuality($compression);
        $im->readImage($pdf);
        if (!is_dir($dir)) {
            throw new \Exception('Dir not found!');
        }
        foreach ($im as $key => $var) {
            $var->setImageFormat($format);
            $filename = rtrim($dir, '/') . '/' . uniqid() . rand(1000, 9999) . '.' . $format;
            if ($var->writeImage($filename) != true) {
                throw new \Exception('Write image fail!');
            }
            $return[] = $filename;
        }
        return $return;
    }

    /**
     * Pdf2img，转换一页pdf
     *
     * @param string $pdf pdf文件路径
     * @param string $dir 保存目录
     * @param string $page 页码
     * @param string $resoulution 分辨率
     * @param string $compression 压缩
     * @param string $format 格式
     *
     * @return string
     */
    public function convertPage($pdf, $dir, $page = 0, $resoulution = '120', $compression = '100', $format = 'png')
    {
        if (!extension_loaded('Imagick')) {
            throw new \Exception('Imagick extension not found!');
        }
        if (!is_file($pdf)) {
            throw new \Exception('Pdf not found!');
        }
        $im = new \Imagick();
        $im->setResolution($resoulution, $resoulution);
        $im->setCompressionQuality($compression);
        $im->readImage($pdf . '[' . $page . ']');
        if (!is_dir($dir)) {
            throw new \Exception('Dir not found!');
        }
        $im->setImageFormat($format);
        $filename = rtrim($dir, '/') . '/' . uniqid() . rand(1000, 9999) . '.' . $format;
        if ($im->writeImage($filename) != true) {
            throw new \Exception('Write image fail!');
        }
        return $filename;
    }
}
