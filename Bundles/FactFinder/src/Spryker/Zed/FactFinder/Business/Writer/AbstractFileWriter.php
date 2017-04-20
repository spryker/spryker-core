<?php

namespace Spryker\Zed\FactFinder\Business\Writer;


abstract class AbstractFileWriter
{

    protected $fileName;

    /**
     * @param string $filePath
     * @param array $data
     * @param bool $append
     * @param string $delimiter
     *
     * @return void
     */
    abstract public function write($filePath, $data, $append = false, $delimiter = ',');

    /**
     * @param $fileName string
     *
     * @return void
     */
    protected function createDirectory($fileName)
    {
        $pathInfo = pathinfo($fileName);
        $directory = $pathInfo['dirname'];

        if (!file_exists($directory)) {
            mkdir($directory);
        }
    }
}