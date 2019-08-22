<?php

/**
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 *
 */

namespace convert;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Class Word2html
 *
 */
class Word2html
{

    private $binary = 'soffice';

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
     *
     * @return string|Exception
     */
    public function convert($word, $htmlPath)
    {
        $process = new Process(
            [
                $this->binary,
                '--headless',
                '--invisible',
                '--convert-to',
                'html:HTML',
                '--outdir',
                $htmlPath,
                realpath($word)
            ]
        );
        $process->setTimeout(10);
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $htmlFile = rtrim($htmlPath, '/'). '/'. pathinfo($word, PATHINFO_FILENAME). '.html';
        return $htmlFile;
    }
}
