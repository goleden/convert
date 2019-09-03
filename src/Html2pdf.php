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
    private $binary = 'wkhtmltopdf';

    /**
     * Construct
     */
    public function __construct($binary = 'wkhtmltopdf')
    {
        $this->binary = $binary;
    }
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
        $snappy = new Pdf($this->binary);
        return $snappy->generateFromHtml($html, $pdfPath);
    }
}
