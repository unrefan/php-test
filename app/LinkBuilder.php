<?php

namespace App;

use App\Concerns\BaseBuilder;

class LinkBuilder extends BaseBuilder
{
    protected $href;
    protected $text;
    /**
     * LinkBuilder constructor.
     * @param $href
     * @param $text
     */
    public function __construct($href, $text)
    {
        parent::__construct(project_path().'/template/link.xhtml');
        $this->href = $href;
        $this->text = $text;
    }

    public static function make($href, $text)
    {
        return (new self($href, $text))->compose();
    }

    protected function compose()
    {
        return TagReplacer::make(
            ['{href}', '{name}'],
            [$this->href, $this->text],
            $this->content
        );
    }
}
