<?php


namespace App;


class FileLoader
{
    protected $url;
    protected $uri;
    protected $file;
    protected $path;
    protected $directories = [];
    protected $lines = [];
    protected $href;
    protected $chunk;
    protected $chunks = [];

    /**
     * FileLoader constructor.
     * @param $url
     * @param $path
     */
    public function __construct($url, $path)
    {
        $this->url = $url;
        $this->path = $path;
    }

    public static function load($url, $path)
    {
        return (new self($url, $path))
            ->read()
            ->prepare();
    }

    public static function render($url, $path)
    {
        self::load($url, $path)->show();
    }

    public function read($path = null)
    {
        if ($handle = opendir($path ?? $this->path)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    array_push($this->directories, $entry);
                }
            }
            closedir($handle);
        }

        return $this;
    }

    public function prepare()
    {
        $this->uri = explode('/', $this->url);
        $this->file = $this->uri[count($this->uri) - 1];
        $filename = preg_replace('/-/', ' ', preg_replace('/.html/', '', $this->file));
        unset($this->uri[count($this->uri) - 1]);
        $this->path = count($this->uri) === 1 ? "/" : implode('/', $this->uri) . "/";
        $dir = preg_replace('/-/', ' ', $this->path);
        $currentDir = $dir === '/' ? '/'.$filename : substr($dir, 0, -1);
        $page = explode(' ', $currentDir);
        $page_number = intval($page[count($page) - 1]) === 0 ? 0 : intval($page[count($page) - 1]) - 1;
        if($page_number > 0) {
            unset($page[count($page) - 1]);
        }
        $currentDir = implode(' ', $page);
        if ($currentDir !== '/') {
            $content = removeBOM(file_get_contents('../data'.$currentDir.'.txt'));
            $content_s = explode('|', $content);
            $this->chunks = array_chunk($content_s, 15);
            $this->href = substr($currentDir, 1, strlen($currentDir));
            foreach ($content_s as $index => $link) {
                $this->lines['/'.slug(file_name($this->href)).'/'.slug($link).'.html'] = find_line_number_by_string($content, $link);
            }
            $this->chunk = $this->chunks[$page_number];
        }

        return $this;
    }

    public function show()
    {
        if ($this->url === '/') {
            $this->renderDirs();
        } else if ($this->file !== '' && count($this->uri) === 1) {
            $this->renderPage()
                ->renderPagination();
        } else if ($this->file !== '' && count($this->uri) > 1 && $this->lines[$this->url]) {
            $this->renderLineNumber();
        } else {
            $this->renderPageNotFound();
        }
    }

    private function renderDirs()
    {
        foreach ($this->directories as $directory) {
            echo "<a href='". slug(file_name($directory)) . '.html' ."'>". file_name($directory) ."</a><br>";
        }

        return $this;
    }

    private function renderPage()
    {
        foreach ($this->chunk as $link) {
            echo "<a href='". $this->path.slug(file_name($this->href)).'/'.slug($link).'.html' ."'>". $link ."</a><br>";
        }

        return $this;
    }

    private function renderPagination()
    {
        if (count($this->chunks) > 1) {
            echo "<a href='". $this->path.slug(file_name($this->href)).'.html' ."'>1</a> ";
            for ($i = 1; $i < count($this->chunks); $i++) {
                echo "<a href='". $this->path.slug(file_name($this->href)).'-'.($i + 1).'.html' ."'>". ($i + 1) ."</a> ";
            }
        }
        return $this;
    }

    private function renderLineNumber()
    {
        echo $this->lines[$this->url];

        return $this;
    }

    private function renderPageNotFound()
    {
        echo "page not found";

        return $this;
    }
}
