<?php

/**
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 *
 */

namespace goleden\convert;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Class Word2pdf
 *
 */
class Word2pdf
{
    private $binary = 'soffice';
    private $file = '';

    /**
     * Construct
     */
    public function __construct($binary = 'soffice')
    {
        $this->binary = $binary;
    }

    /**
     * soffice
     *
     * @param string $word     文件
     * @param string $path     pdf生成路径
     * @param int    $timeout  执行超时时间，单位秒
     *
     * @return string|Exception
     */
    public function convert($word, $path, $timeout = 30)
    {
        $path = rtrim($path, '/');
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $process = new Process(
            [
                $this->binary,
                '--headless',
                '--invisible',
                '--convert-to',
                'pdf',
                '--outdir',
                $path,
                realpath($word)
            ]
        );
        $process->setTimeout($timeout);
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $this->file = $path . '/' . pathinfo($word, PATHINFO_FILENAME) . '.pdf';

        return $this->file;
    }
}
