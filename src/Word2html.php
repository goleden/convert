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
     *     'uploadCallback' => function () {} // 匿名函数，自定义上传
     * ]
     *
     * @var [type]
     * @author guoruidian
     * @date 2020-04-22
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
     * Call a callback with the arguments.
     *
     * @param mixed $callback
     * @return null|mixed
     */
    private function call($callback, array $args = [])
    {
        $result = null;
        if ($callback instanceof \Closure) {
            $result = $callback(...$args);
        } elseif (is_object($callback) || (is_string($callback) && function_exists($callback))) {
            $result = $callback(...$args);
        } elseif (is_array($callback)) {
            [$object, $method] = $callback;
            $result = is_object($object) ? $object->{$method}(...$args) : $object::$method(...$args);
        } else {
            $result = call_user_func_array($callback, $args);
        }
        return $result;
    }
    
    /**
     * 格式化内容
     */
    protected function parseHtmlContent()
    {
        $image = [];
        $htmlContent = file_get_contents($this->htmlFile);
        // 内容图片转base64
        preg_match_all('/(<img.*?src=")(.*?)(".*?>)/is', $htmlContent, $imgFiles);
        if (!empty($imgFiles[0])) {
            $replace = [];
            foreach ($imgFiles[0] as $key => $imgHtml) {
                $imgFile = $this->htmlPath . '/' . $imgFiles[2][$key];
                if (isset($this->options['imageCallback'])) {
                    $src = $this->call($this->options['imageCallback'], [realpath($imgFile)]);
                } else {
                    $src = $this->imgToBase64($imgFile);
                }
                $replace[] = $imgFiles[1][$key] . $src . $imgFiles[3][$key];
            }
            $htmlContent = str_replace($imgFiles[0], $replace, $htmlContent);
        }
        // 删除文件夹
        $this->delDir($this->htmlPath);
        $this->htmlFile = $this->htmlPath . '.html';
        file_put_contents($this->htmlFile, $htmlContent);
    }

    protected function delDir($dir)
    {
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
            $base64 = 'data:image/' . $imgType . ';base64,' . $file_content;
        }
    
        return $base64;
    }
}
