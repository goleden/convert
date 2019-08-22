<?php

require '../vendor/autoload.php';

use convert\Html2pdf;

try {
    $html2pdf = new Html2pdf();
    $html2pdf->convert('<div>1234</div>', './pdf/test.pdf');
    echo 'success';
} catch (\Exception $e) {
    echo ($e->getMessage());
}
