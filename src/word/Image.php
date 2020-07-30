<?php

namespace goleden\convert\word;

use goleden\convert\utils\Common;

class Image extends Decorator
{
    /**
     * 回调
     * @var mixed
     */
    public $callback;
    public $htmlPath;

    public function getContent()
    {
        $content = $this->parseContent->getContent();

        // 内容图片转base64
        preg_match_all('/(<img.*?src=")(.*?)(".*?>)/is', $content, $imgFiles);
        if (!empty($imgFiles[0])) {
            $replace = [];
            foreach ($imgFiles[0] as $key => $imgHtml) {
                $imgFile = $this->htmlPath . '/' . $imgFiles[2][$key];
                if ($this->callback) {
                    $src = Common::call($this->callback, [realpath($imgFile)]);
                } else {
                    $src = $this->imgToBase64($imgFile);
                }
                $replace[] = $imgFiles[1][$key] . $src . $imgFiles[3][$key];
            }
            $content = str_replace($imgFiles[0], $replace, $content);
        }


        return $content;
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
