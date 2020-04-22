<?php

namespace goleden\converttest;

use PHPUnit\Framework\TestCase;
use goleden\convert\Word2html;

class ConvertTest extends TestCase
{
    public function testPushAndPop()
    {
        $stack = [];
        $this->assertEquals(0, count($stack));

        array_push($stack, 'foo');
        $this->assertEquals(1, count($stack));
        $this->assertEquals('foo', array_pop($stack));
        $this->assertEquals(0, count($stack));
    }

    public function testWord2html()
    {
        $html2pdf = new Word2html();
        $file = $html2pdf->convert(dirname(__DIR__) . '/examples/1.doc', dirname(__DIR__) . '/examples/html/', 30, ['uploadCallback' => function ($data) {
            return $data;
        }]);
        $this->assertEquals(true, !empty($file));
    }
}
