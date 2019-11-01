<?php

namespace App;

class TagReplacer
{
    protected $data;

    /**
     * TagReplacer constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function replace($tag, $replacement)
    {
        $this->data = preg_replace('/'. $tag .'/', $replacement, $this->data);

        return $this;
    }

    public function getData() {
        return $this->data;
    }

    public static function make($tags, $replacements, $data)
    {
        $replacer = new self($data);

        foreach ($tags as $index => $tag) {
            $replacer->replace($tag, $replacements[$index]);
        }

        return $replacer->getData();
    }
}
