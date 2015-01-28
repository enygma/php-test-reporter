<?php

namespace CodeClimate\Bundle\TestReporterBundle\System;

class Git
{
    /**
     * Generate the git information array
     *
     * @return array Git repository information
     */
    public static function evaluate()
    {
        return array(
            'head' => self::getHead(),
            'branch' => self::getBranch(),
            'committed_at' => self::getCommittedAt()
        );
    }

    /**
     * Get HEAD repository information
     *
     * @return string HEAD commit information
     */
    public static function getHead()
    {
        exec("git log -1 --pretty=format:'%H\n%aN\n%ae\n%cN\n%ce\n%s\n%cd'", $result);
        return $result[0];
    }

    /**
     * Get the current branch
     *
     * @return string Branch name
     */
    public static function getBranch()
    {
        exec('git branch', $branchResult);
        $branchResult = str_replace('* ', '', $branchResult);
        return $branchResult[0];
    }

    /**
     * Get the last commit date on the current repository
     *
     * @return integer Unix timestamp of last commit
     */
    public static function getCommittedAt()
    {
        exec("git log -1 --pretty=format:'%H\n%aN\n%ae\n%cN\n%ce\n%s\n%cd'", $result);
        $commitDate = new \DateTime($result[6]);
        return (int)$commitDate->format('U');
    }
}