<?php

namespace Spryker\Zed\FactFinder\Business\Writer;


class CsvFileWriter extends AbstractFileWriter
{

    /**
     * @param string $filePath
     * @param array $data
     * @param bool $append
     * @param string $delimiter
     *
     * @return void
     */
    public function write($filePath, $data, $append = false, $delimiter = ',')
    {
        $this->createDirectory($filePath);

        if ($append) {
            $filePointer = fopen($filePath, 'a');
        } else {
            $filePointer = fopen($filePath, 'w');
        }

        foreach ($data as $row) {
            fputcsv($filePointer, $row, $delimiter);
        }

        fclose($filePointer);
    }

}
