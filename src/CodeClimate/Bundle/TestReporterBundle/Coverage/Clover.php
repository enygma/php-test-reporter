<?php

namespace CodeClimate\Bundle\TestReporterBundle\Coverage;

class Clover
{
    private $path;
    private $content;

    public function __construct($path)
    {
        $this->path = $path;
        $this->content = simplexml_load_string(file_get_contents($path));
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getRunAt()
    {
        return (int)$this->content['generated'];
    }

    public function getFiles()
    {
        return $this->content->project->package->file;
    }
}