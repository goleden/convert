<?php

require '../vendor/autoload.php';

use goleden\convert\Html2pdf;
use goleden\convert\Word2html;
use goleden\convert\Word2pdf;
use goleden\convert\word;

// try {
//     $html2pdf = new Html2pdf();
//     $html2pdf->convert('<div>1234</div>', './pdf/test.pdf');
//     echo 'success';
// } catch (\Exception $e) {
//     echo ($e->getMessage());
// }

// try {
//     $html2pdf = new Word2html();
//     $html2pdf->convert('./1.doc', './html/');
//     echo 'success';
// } catch (\Exception $e) {
//     echo ('Word2html:' . $e->getMessage());
// }
try {
    $html2pdf = new Word2pdf();
    $path = $html2pdf->convert('./1.doc', './html/');
    echo $path . "\n";
    echo 'success' . "\n";
    exit;
} catch (\Exception $e) {
    echo ('Word2html:' . $e->getMessage());
}

try {
    $html2pdf = new Word2html();
    // $doc = './lineheight.docx';
    $doc = './1.doc';
    // $doc = './b.doc';
    $file = $html2pdf->convert($doc, realpath('./html/'), 30, [
        'imageCallback1' => function ($file) {
            return $file;
        },
        'contentCallback' => function (word\ParseContent $parseContent) {
            $space = new word\Space($parseContent);
            $htmlContent = $space->getContent();
        
            $lineHeight = new word\LineHeight($space);
            $htmlContent = $lineHeight->getContent();
            return $htmlContent;
        }
    ]);
    echo $file . "\n";

    echo 'success';
} catch (\Exception $e) {
    echo ('Word2html:' . $e->getMessage());
}
