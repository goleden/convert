<?php

namespace goleden\convert\word;

class Space extends Decorator
{
    public function getContent()
    {
        $content = $this->parseContent->getContent();

        // 空格转换
        $content = preg_replace_callback(
            "|(<[^<>]+>)(([^<>]*( )+[^<>]*)+)(<[^<>]+>)|U",
            function ($matches) {
                return $matches[1] . str_replace('  ', '&nbsp;&nbsp;', $matches[2]) . $matches[5];
            },
            $content
        );

        return $content;
    }
}
