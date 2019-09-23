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
    private $htmlFile = '';

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
        $this->htmlPath = rtrim($htmlPath, '/'). '/'. uniqid();
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
        $process->setTimeout(10);
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $this->htmlFile = $this->htmlPath. '/'. pathinfo($word, PATHINFO_FILENAME). '.html';

        $this->htmlContentImgToBase64();

        return $this->htmlFile;
    }

    /**
     * 内容图片转base64
     */
    protected function htmlContentImgToBase64()
    {
        $image = [];
        $htmlContent = file_get_contents($this->htmlFile);
        preg_match_all('/(<img.*?src=")(.*?)(".*?>)/is', $htmlContent, $imgFiles);

        if (!empty($imgFiles[0])) {
            $base64 = $this->imgToBase64($this->htmlPath. '/'. $imgFiles[2][$key]);
            $replace = [];
            foreach ($imgFiles[0] as $key => $imgHtml) {
                $replace[] = $imgFiles[1][$key]. $base64. $imgFiles[3][$key];
            }
            $htmlContent = str_replace($imgFiles[0], $replace, $htmlContent);
            file_put_contents($htmlFile, $htmlContent);
        }
    }

    protected function imgToBase64($imgFile)
    {
        //返回图片的base64
        $base64 = '';
        if (file_exists($imgFile)) {
            $imgInfo = getimagesize($imgFile); // 取得图片的大小，类型等
            $content = file_get_contents($imgFile);
            $file_content = chunk_split(base64_encode($content)); // base64编码
            // 判读图片类型
            switch ($imgInfo[2]) {
                case 1:
                    $imgType = "gif";
                    break;
                case 2:
                    $imgType = "jpg";
                    break;
                case 3:
                    $imgType = "png";
                    break;
                default:
                    $imgType = "png";
                    break;
            }
            // 合成图片的base64编码
            $base64 = 'data:image/'. $imgType. ';base64,'. $file_content;
        }
    
        return $base64;
    }
}
