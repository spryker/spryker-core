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
     * CsvWriter constructor.
     * @param string $filename
     */
    public function __construct($filename = 'test.csv')
    {
        $this->csvFilename = $filename;
    }

    /**
     * @param string $filename
     *
     * @return \SplFileObject
     */
    protected function createCsvFile($filename)
    {
        $csvFile = new SplFileObject($filename, 'w');
        $csvFile->setCsvControl(',', '"');
        $csvFile->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY | SplFileObject::READ_AHEAD);

        return $csvFile;
    }

    /**
     * @return \SplFileObject
     *
     * @throws \LogicException
     */
    public function getFile()
    {
        if ($this->csvFile === null) {
            $this->csvFile = $this->createCsvFile($this->csvFilename);
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
            $result = $this->getFile()->fputcsv($row);
        }
        return $result;
    }

}
