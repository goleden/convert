<?php

/**
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 *
 */

namespace convert;

use Knp\Snappy\Pdf;

/**
 * Class Html 2 pdf
 *
 */
class Html2pdf
{
    /**
     * Wkhtmltopdf
     *
     * @param string $html    html代码块
     * @param string $pdfPath pdf生成路径
     *
     * @return string
     */
    public function convert($html, $pdfPath)
    {
        $isWin = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? true : false;
        if ($isWin) {
            $bin = realpath('../vendor/wemersonjanuario/wkhtmltopdf-windows/bin/wkhtmltopdf64.exe');
        } else {
            $bin = realpath('../vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64');
        }
        if (!is_file($bin)) {
            throw new \Exception('file is not exists!');
        }
        $snappy = new Pdf($bin);
        return $snappy->generateFromHtml($html, $pdfPath);
    }
}
