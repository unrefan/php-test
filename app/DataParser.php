<?php

namespace App;

class DataParser
{
    protected $path;
    protected $delimiter;
    protected $data;
    protected $content;
    protected $links;
    protected $directory;
    protected $chunks;

    /**
     * DataParser constructor.
     * @param $directory
     * @param $path
     * @param string $delimiter
     */
    public function __construct($directory, $path, $delimiter = '|')
    {
        $this->directory = $directory;
        $this->path = $path;
        $this->delimiter = $delimiter;
        $this->content = file_get_contents($path);;
    }

    public static function parse($directory, $path, $delimiter = '|')
    {
        return (new self($directory, $path, $delimiter))
            ->split()
            ->create();
    }

    public function split() {
        $this->data = explode($this->delimiter, $this->content);

        return $this;
    }

    public function create()
    {
        $this->links = [];

        foreach ($this->data as $index => $name) {

            $href = slug($name);
            $href_suffixed = $href.'-'.$index;

            $file = $this->getFilePath($href);
            $file_suffixed = $this->getFilePath($href_suffixed);

            if (file_exists($file)) {
                PageBuilder::create($name, $name, $file_suffixed);
                array_push($this->links, LinkBuilder::make($href_suffixed, $name));
            } else {
                PageBuilder::create($name, $name, $file);
                array_push($this->links, LinkBuilder::make($href, $name));
            }
        }

        return $this;
    }

    public function paginate($perPage = 15)
    {
        $this->chunks = array_chunk($this->links, $perPage);

        $links = $this->createLinks();

        for($i = 1; $i < count($this->chunks); $i++) {
            $page = ($i + 1);
            $name = $this->directory.'-'.$page;
            array_push($this->chunks[$i], $links);

            PageBuilder::create(
                $this->directory,
                implode('<br>', $this->chunks[$i]),
                $this->getFilePath($name)
            );
        }

        array_push($this->chunks[0], $links);
        PageBuilder::create(
            $this->directory,
            implode('<br>', $this->chunks[0]),
            $this->getFilePath('index')
        );

        return $this;
    }

    private function createLinks()
    {
        $paginate = [];
        array_push($paginate, LinkBuilder::make($this->href('index'), 1));
        for($i = 1; $i < count($this->chunks); $i++) {
            $page = ($i + 1);
            $name = $this->directory.'-'.$page;
            array_push($paginate, LinkBuilder::make($this->href($name), $page));
        }
        return implode(' ', $paginate);
    }

    private function href($name)
    {
        return '/'.$this->directory.'/'.$name.'.html';
    }

    private function getFilePath($filename) {
        return project_path().'/public/'.$this->directory.'/'.$filename.'.html';
    }
}
