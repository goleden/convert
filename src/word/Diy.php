<?php

namespace goleden\convert\word;

use goleden\convert\utils\Common;

class Diy extends Decorator
{
    /**
     * 回调
     * @var mixed
     */
    public $callback;

    public function getContent()
    {
        $content = $this->parseContent->getContent();
        $content = Common::call($this->callback, [$this->parseContent]);
        
        return $content;
    }
}
