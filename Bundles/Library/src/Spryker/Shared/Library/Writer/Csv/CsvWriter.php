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
     * @param \Spryker\Shared\Library\Writer\Csv\CsvFormatter $csvFormatter
     *
     * @return void
     */
    public function setCsvFormat($csvFormatter)
    {
        $this->csvDelimiter = $csvFormatter->getDelimiter();
        $this->csvEnclosure = $csvFormatter->getEnclosure();
        $this->csvEscape = $csvFormatter->getEscape();
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
     * @param array $headerKeys
     *
     * @return \SplFileObject
     */
    public function getFile($headerKeys = [])
    {
        if ($this->csvFile === null) {
            $this->csvFile = $this->createCsvFile($this->csvFilename);
            $this->csvFile->fputcsv($headerKeys);
        }

        return $this->csvFile;
    }

    /**
     * @param array $data
     *
     * @return int
     */
    public function write($data)
    {
        $result = 0;
        foreach ($data as $key => $row) {
            $result = $this->getFile(array_keys($row))->fputcsv($row);
        }
        return $result;
    }

}
