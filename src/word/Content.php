<?php

namespace goleden\convert\word;

class Content implements ParseContent
{
    /**
     * @var string
     */
    protected $content;

    /**
     * constructor.
     *
     * @param string $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }
}
