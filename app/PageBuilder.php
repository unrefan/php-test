<?php

namespace App;

use App\Concerns\BaseBuilder;

class PageBuilder extends BaseBuilder
{
    protected $data;
    protected $title;

    /**
     * PageBuilder constructor.
     * @param $title
     * @param $data
     */
    public function __construct($title, $data)
    {
        $this->data = $data;
        $this->title = $title;
        parent::__construct(project_path() . '/template/master.xhtml');
    }

    public static function make($title, $data)
    {
        return (new self($title, $data))->compose();
    }

    public static function create($title, $data, $destination)
    {
        return file_put_contents($destination, self::make($title, $data));
    }

    protected function compose() {
        return TagReplacer::make(
            ['{title}', '{body}'],
            [$this->title, $this->data],
            $this->content
        );
    }
}
