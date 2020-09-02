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
     * @param string $word     html代码块
     * @param string $htmlPath pdf生成路径
     * @param int    $timeout  执行超时时间，单位秒
     *
     * @return string|Exception
     */
    public function convert($word, $path, $timeout = 30, $options = [])
    {
        $this->options = $options;
        $this->path = rtrim($path, '/') . '/' . uniqid();
        if (!is_dir($this->htmlPath)) {
            mkdir($this->htmlPath, 0777, true);
        }
        $process = new Process(
            [
                $this->binary,
                '--headless',
                '--invisible',
                '--convert-to',
                'pdf',
                '--outdir',
                $this->path,
                realpath($word)
            ]
        );
        $process->setTimeout($timeout);
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $this->file = $this->path . '/' . pathinfo($word, PATHINFO_FILENAME) . '.pdf';

        return $this->file;
    }
}
