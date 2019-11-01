<?php

namespace App\Concerns;

abstract class BaseBuilder
{
    protected $template;
    protected $content;

    /**
     * BaseBuilder constructor.
     * @param $template
     */
    public function __construct($template)
    {
        $this->template = $template;
        $this->content = file_get_contents($this->template);
    }

    protected abstract function compose();
}
