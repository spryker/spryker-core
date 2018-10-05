<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilDataReader\Model\Reader\Csv;

use Exception;
use SplFileObject;
use UnexpectedValueException;

class CsvReader implements CsvReaderInterface
{
    /**
     * @var \SplFileObject|null
     */
    protected $csvFile;

    /**
     * @var \Spryker\Service\UtilDataReader\Model\Reader\Csv\CsvMetaInterface|null
     */
    protected $csvMeta;

    /**
     * @var string|null
     */
    protected $csvFilename;

    /**
     * @var int
     */
    protected $readIndex = 1;

    /**
     * @param string $filename
     *
     * @throws \Exception
     *
     * @return \SplFileObject
     */
    protected function createCsvFile($filename)
    {
        if (!is_file($filename) || !is_readable($filename)) {
            throw new Exception(sprintf(
                'Could not open CSV file "%s"',
                $filename
            ));
        }

        $csvFile = new SplFileObject($filename);
        $csvFile->setCsvControl(',', '"');
        $csvFile->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY | SplFileObject::READ_AHEAD);

        return $csvFile;
    }

    /**
     * @param string $filename
     *
     * @return \Spryker\Service\UtilDataReader\Model\Reader\Csv\CsvMetaInterface
     */
    protected function createCsvMeta($filename)
    {
        $csvFile = $this->createCsvFile($filename);
        return new CsvMeta($csvFile);
    }

    /**
     * @return \Spryker\Service\UtilDataReader\Model\Reader\Csv\CsvMetaInterface
     */
    public function getCsvMeta()
    {
        if ($this->csvMeta === null) {
            $this->csvMeta = $this->createCsvMeta($this->getFile()->getPathname());
        }

        return $this->csvMeta;
    }

    /**
     * @param array $columns
     * @param array $data
     *
     * @return array
     */
    public function composeItem(array $columns, array $data)
    {
        if (count($columns) !== count($data)) {
            return [];
        }

        return array_combine(
            array_values($columns),
            array_values($data)
        );
    }

    /**
     * @param array $columns
     * @param array $data
     * @param string $filename
     * @param int $lineNumber
     *
     * @throws \UnexpectedValueException
     *
     * @return array
     */
    protected function composeAndValidateLine(array $columns, array $data, $filename, $lineNumber)
    {
        $data = array_values($data);
        $columns = array_values($columns);

        if (empty($data)) {
            throw new UnexpectedValueException(sprintf(
                'Expected %d column(s) but received data with %d column(s) in %s on line %d',
                count($columns),
                count($data),
                $filename,
                $lineNumber
            ));
        }

        return $this->composeItem($columns, $data);
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->getCsvMeta()->getColumns();
    }

    /**
     * @return \SplFileObject
     */
    public function getFile()
    {
        if ($this->csvFile === null) {
            $this->csvFile = $this->createCsvFile($this->csvFilename);
        }

        return $this->csvFile;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->getCsvMeta()->getTotal();
    }

    /**
     * @param string $filename
     *
     * @return $this
     */
    public function load($filename)
    {
        $this->csvFilename = $filename;
        $this->csvFile = null;
        $this->csvMeta = null;
        $this->readIndex = 1;

        $this->getFile()->rewind();

        return $this;
    }

    /**
     * @throws \UnexpectedValueException
     *
     * @return array
     */
    public function read()
    {
        $data = $this->getFile()->fgetcsv();
        if (empty($data)) {
            throw new UnexpectedValueException(sprintf(
                'Malformed data at line %d in %s',
                $this->readIndex,
                $this->csvFilename
            ));
        }

        $data = $this->composeAndValidateLine(
            $this->getCsvMeta()->getColumns(),
            $data,
            $this->getFile()->getRealPath(),
            $this->readIndex + 1
        );

        $this->readIndex++;

        return $data;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return !$this->getFile()->eof();
    }

    /**
     * @param bool $skipColumns
     *
     * @return void
     */
    public function rewind($skipColumns = true)
    {
        $this->csvFile->fseek(0);

        if ($skipColumns) {
            $this->getFile()->fseek($this->getCsvMeta()->getColumnsOffset());
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $data = [];
        $csvFile = $this->createCsvFile($this->getFile()->getPathname());
        $csvMeta = $this->createCsvMeta($this->getFile()->getPathname());
        $currentLine = 1;

        $csvFile->rewind();
        while (!$csvFile->eof()) {
            $line = $csvFile->fgetcsv();
            if (!$line) {
                break;
            }

            $data[] = $this->composeAndValidateLine(
                $csvMeta->getColumns(),
                $line,
                $csvFile->getRealPath(),
                $currentLine++
            );
        }

        return $data;
    }
}
