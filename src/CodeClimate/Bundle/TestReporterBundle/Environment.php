<?php

namespace CodeClimate\Bundle\TestReporterBundle;

class Environment
{
    /**
     * Gather the needed environment information
     *
     * @return array Environment data
     */
    public static function evaluate()
    {
        return array(
            'pwd' => getcwd(),
            'package_version' => Version::VERSION
        );
    }
}