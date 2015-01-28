<?php

namespace CodeClimate\Bundle\TestReporterBundle;

class SourceFile
{
    private $clover;

    public static function evaluate($clover)
    {
        $sourceFiles = array();
        foreach ($clover->getFiles() as $file) {
            $fileContents = file_get_contents($file['name']);
            $lines = count(explode("\n", $fileContents));
            $lineCoverage = array_fill(1, $lines, null);

            foreach ($file->line as $line) {
                $number = $line['num'];
                if ((string)$line['type'] === 'stmt') {
                    $lineCoverage[(int)$number] = (int)$line['count'];
                }
            }

            $coverageList = array();
            foreach ($lineCoverage as $coverage) {
                $coverageList[] = ($coverage === null) ? 'null' : $coverage;
            }
            $coverageString = implode(',', $coverageList);

            $sourceFiles[] = array(
                'name' => str_replace(getcwd().'/', '', $file['name']),
                'coverage' => '['.$coverageString.']',
                'blob_id' => $this->generateBlobId($fileContents)
            );
        }
        return $sourceFiles;
    }

    public function generateBlobId($contents)
    {
        return sha1('blob '.strlen($fileContents)."\0".$fileContents);
    }
}