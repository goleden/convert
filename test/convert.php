<?php

require '../../../../vendor/autoload.php';

use convert\Html2pdf;
use convert\Word2html;

try {
    $html2pdf = new Html2pdf();
    $html2pdf->convert('<div>1234</div>', './pdf/test.pdf');
    echo 'success';
} catch (\Exception $e) {
    echo ($e->getMessage());
}

try {
    $html2pdf = new Word2html();
    $html2pdf->convert('./1.doc', './html/');
    echo 'success';
} catch (\Exception $e) {
    echo ($e->getMessage());
}
