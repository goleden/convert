<?php

namespace goleden\convert\word;

abstract class Decorator implements ParseContent
{
    /**
     * @var ParseContent
     */
    protected $parseContent;

    /**
     * Decorator constructor.
     *
     * @param ParseContent $parseContent
     */
    public function __construct(parseContent $parseContent)
    {
        $this->parseContent = $parseContent;
    }
}
