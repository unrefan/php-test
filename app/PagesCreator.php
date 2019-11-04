<?php


namespace App;

class PagesCreator
{
    protected $path;
    protected $directories;

    /**
     * PagesCreator constructor.
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    public static function make($path)
    {
        (new self($path))
            ->read()
            ->check()
            ->create();
    }

    public function read()
    {
        $this->directories = [];
        if ($handle = opendir(project_path().'/'.$this->path)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    array_push($this->directories, [
                        'name' => file_name($entry),
                        'file' => $entry
                    ]);
                }
            }

            closedir($handle);
        }

        return $this;
    }

    public function create()
    {
        foreach ($this->directories as $directory) {
            $this->createDir($directory['name']);

            DataParser::parse($directory['name'], project_path().'/'.$this->path.'/'.$directory['file'])->paginate();
        }

        return $this;
    }

    public function check() {
        foreach ($this->directories as $directory) {
            if (file_exists(project_path().'/public/'.$directory['name'])) {
                $this->clear(project_path().'/public/'.$directory['name']);
            }
        }

        return $this;
    }

    public function clear($path)
    {
        if(is_dir($path)) {
            $files = glob( $path . '*', GLOB_MARK);

            foreach( $files as $file ){
                $this->clear($file);
            }

            rmdir($path);
        } elseif (is_file($path)) {
            unlink($path);
        }
    }

    public function render()
    {
        foreach ($this->directories as $directory) {
            echo LinkBuilder::make('/'.$directory['name'], $directory['name'])."<br>";
        }
    }

    private function createDir($name)
    {
        mkdir(project_path().'/public/'.$name, 0777, true);

        return $this;
    }
}
