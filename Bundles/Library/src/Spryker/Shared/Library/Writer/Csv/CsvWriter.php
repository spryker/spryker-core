<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Writer\Csv;

use SplFileObject;

class CsvWriter implements CsvWriterInterface
{

    /**
     * @var \SplFileObject
     */
    protected $csvFile;

    /**
     * @var string
     */
    protected $csvFilename;

    /**
     * @var string
     */
    protected $csvDelimiter = ",";

    /**
     * @var string
     */
    protected $csvEnclosure = "\"";

    /**
     * @var string
     */
    protected $csvEscape = "\\";

    /**
     * CsvWriter constructor.
     * @param string $filename
     */
    public function __construct($filename = 'test.csv')
    {
        $this->csvFilename = $filename;
    }

    /**
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     *
     * @return void
     */
    public function setCsvFormat($delimiter = ",", $enclosure = "\"", $escape = "\\")
    {
        $this->csvDelimiter = $delimiter;
        $this->csvEnclosure = $enclosure;
        $this->csvEscape = $escape;
    }

    /**
     * @param string $filename
     *
     * @return \SplFileObject
     */
    protected function createCsvFile($filename)
    {
        $csvFile = new SplFileObject($filename, 'w');
        $csvFile->setCsvControl($this->csvDelimiter, $this->csvEnclosure, $this->csvEscape);

        return $csvFile;
    }

    /**
     * @return \SplFileObject
     */
    public function getFile()
    {
        return $this->csvFile;
    }

    /**
     * @param array $data_row
     *
     * @return void
     */
    protected function initializeHeaderColumns(array $data_row)
    {
        if ($this->csvFile === null) {
            $this->csvFile = $this->createCsvFile($this->csvFilename);
            $this->csvFile->fputcsv(array_keys($data_row));
        }
    }

    /**
     * @param array $data
     *
     * @return int
     */
    public function write(array $data)
    {
        $result = 0;

        $this->initializeHeaderColumns(current($data));
        foreach ($data as $key => $row) {
            $result = $this->getFile()->fputcsv($row);
        }
        return $result;
    }

}
