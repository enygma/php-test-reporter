<?php

namespace CodeClimate\Bundle\TestReporterBundle\Coverage;

class Clover
{
    /**
     * Clover.xml file path
     * @var string
     */
    private $path;

    /**
     * Clover file content (saved as SimpleXML object)
     * @var object
     */
    private $content;

    /**
     * Init the object and set the path & content
     *
     * @param string $path Path to clover file
     */
    public function __construct($path)
    {
        $this->path = $path;
        $this->content = simplexml_load_string(file_get_contents($path));
    }

    /**
     * Get the current file path
     *
     * @return string Clover file path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get the "run at" time for this clover file
     *
     * @return integer Unix timestamp of last run
     */
    public function getRunAt()
    {
        return (int)$this->content['generated'];
    }

    /**
     * Get the set of files covered in this coverage report
     *
     * @return array Set of covered file details
     */
    public function getFiles()
    {
        return $this->content->project->package->file;
    }
}