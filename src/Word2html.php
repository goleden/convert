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
 * Class Word2html
 *
 */
class Word2html
{
    private $binary = 'soffice';
    private $htmlFile = '';
    /**
     * 可选项
     * [
     *     'imageCallback' => function ($imageFile) {} // 匿名函数，自定义上传
     *     'contentCallback' => function (word\ParseContent $parseContent) {} // 匿名函数，格式化内容
     * ]
     *
     * @var [type]
     * @author guoruidian
     */
    protected $options;

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
    public function convert($word, $htmlPath, $timeout = 30, $options = [])
    {
        $this->options = $options;
        $this->htmlPath = rtrim($htmlPath, '/') . '/' . uniqid();
        if (!is_dir($this->htmlPath)) {
            mkdir($this->htmlPath, 0777, true);
        }
        $process = new Process(
            [
                $this->binary,
                '--headless',
                '--invisible',
                '--convert-to',
                'html:HTML',
                '--outdir',
                $this->htmlPath,
                realpath($word)
            ]
        );
        $process->setTimeout($timeout);
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $this->htmlFile = $this->htmlPath . '/' . pathinfo($word, PATHINFO_FILENAME) . '.html';

        $this->parseHtmlContent();

        return $this->htmlFile;
    }

    /**
     * 格式化内容
     */
    protected function parseHtmlContent()
    {
        $image = [];
        $htmlContent = file_get_contents($this->htmlFile);
    
        $content = new word\Content($htmlContent);
        $htmlContent = $content->getContent();

        $image = new word\Image($content);
        $image->callback = $this->options['imageCallback'] ?? null;
        $image->htmlPath = $this->htmlPath;
        $htmlContent = $image->getContent();

        if (isset($this->options['contentCallback'])) {
            $diy = new word\Diy($image);
            $diy->callback = $this->options['contentCallback'];
            $htmlContent = $diy->getContent();
        }

        $this->delDir($this->htmlPath);

        $this->htmlFile = $this->htmlPath . '.html';
        file_put_contents($this->htmlFile, $htmlContent);
    }
      
    protected function delDir($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }
        // echo $dir;
        //先删除目录下的文件：
        $dh = opendir($dir);
        while ($file = readdir($dh)) {
            if ($file != "." && $file != "..") {
                $fullpath = $dir . "/" . $file;
                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    $this->delDir($fullpath);
                }
            }
        }
        closedir($dh);
        //删除当前文件夹：
        if (rmdir($dir)) {
            return true;
        } else {
            return false;
        }
    }
}
