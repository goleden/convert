<?php

namespace goleden\convert\word;

class LineHeight extends Decorator
{
    public function getContent()
    {
        $content = $this->parseContent->getContent();

        $content = preg_replace_callback(
            "|(style=[\"'].*)(line-height:.*;?)([\"'])|Ui",
            function ($matches) {
                // var_dump($matches);
                return $matches[1] . $matches[3];
            },
            $content
        );

        return $content;
    }
}
