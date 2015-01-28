<?php

namespace CodeClimate\Bundle\TestReporterBundle;

class SourceFile
{
    private $clover;

    /**
     * Run the evaluation on the clover coverage data
     *
     * @param Entity\Clover $clover Clover coverage file
     * @return array Source file information
     */
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
                'blob_id' => self::generateBlobId($fileContents)
            );
        }
        return $sourceFiles;
    }

    /**
     * Generate the blob ID hash (sha1)
     *
     * @param string $contents File contents
     * @return string SHA1 hash for blob id
     */
    public function generateBlobId($contents)
    {
        return sha1('blob '.strlen($contents)."\0".$contents);
    }
}