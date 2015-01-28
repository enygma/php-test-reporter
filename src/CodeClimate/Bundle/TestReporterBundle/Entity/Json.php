<?php

namespace CodeClimate\Bundle\TestReporterBundle\Entity;

use CodeClimate\Bundle\TestReporterBundle\Environment;
use CodeClimate\Bundle\TestReporterBundle\System\Git;
use CodeClimate\Bundle\TestReporterBundle\SourceFile;
use CodeClimate\Bundle\TestReporterBundle\Entity\CiInfo;

class Json
{
    /**
     * Is result partial?
     * @var boolean
     */
    private $partial = false;

    /**
     * When the last coverage was run at
     * @var integer
     */
    private $run_at;

    /**
     * Repository token (from CC)
     * @var string
     */
    private $repo_token;

    /**
     * Environment information
     * @var array
     */
    private $environment = array();

    /**
     * Git repository information
     * @var array
     */
    private $git = array();

    /**
     * Continuous integration service information
     * @var array
     */
    private $ci_service = array();

    /**
     * Set of source files
     * @var array
     */
    private $source_files = array();

    /**
     * Set of coverage information
     * @var array
     */
    private $coverage = array();

    /**
     * Init the object and set the coverage details and repo token
     *
     * @param array $coverage Coverage information
     * @param string $token Repository token
     */
    public function __construct(array $coverage, $token)
    {
        $this->coverage = $coverage;
        $this->repo_token = $token;
    }

    /**
     * Conver the JSON information into an encoded string
     *
     * @return string JSON string output
     */
    public function __toString()
    {
        $sourceFiles = $this->buildSourceFiles($this->coverage);
        $ci = new CiInfo();

        $json = array(
            'partial' => $this->partial,
            'run_at' => $this->run_at,
            'repo_token' => $this->repo_token,
            'environment' => Environment::evaluate(),
            'git' => Git::evaluate(),
            'ci_service' => $ci->toArray(),
            'source_files' => $sourceFiles
        );
        return json_encode($json);
    }

    /**
     * Build the "source files" information
     *
     * @param array $coverage Coverage information
     * @return array Set of source file results
     */
    public function buildSourceFiles(array $coverage)
    {
        $files = array();
        foreach ($coverage as $cover) {
            $this->run_at = $cover->getRunAt();
            $files = array_merge($files, SourceFile::evaluate($cover));
        }
        return $files;
    }
}