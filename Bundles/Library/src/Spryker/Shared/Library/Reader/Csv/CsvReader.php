<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Reader\Csv;

use SplFileObject;
use Spryker\Shared\Library\Reader\Exception\ResourceNotFoundException;

class CsvReader implements CsvReaderInterface
{

    /**
     * @var \SplFileObject
     */
    protected $csvFile;

    /**
     * @var \Spryker\Shared\Library\Reader\Csv\CsvMetaInterface
     */
    protected $csvMeta;

    /**
     * @var string
     */
    protected $csvFilename;

    /**
     * @var int
     */
    protected $readIndex = 1;

    /**
     * @param string $filename
     *
     * @return \SplFileObject
     *
     * @throws \Spryker\Shared\Library\Reader\Exception\ResourceNotFoundException
     */
    protected function createCsvFile($filename)
    {
        if (!is_file($filename) || !is_readable($filename)) {
            throw new ResourceNotFoundException(sprintf(
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
     * @return \Spryker\Shared\Library\Reader\Csv\CsvMeta
     */
    protected function createCsvMeta($filename)
    {
        $csvFile = $this->createCsvFile($filename);
        return new CsvMeta($csvFile);
    }

    /**
     * @return \Spryker\Shared\Library\Reader\Csv\CsvMeta
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
        $data = array_values($data);
        $columns = array_values($columns);
        $columnCount = count($columns);
        $dataCount = count($data);

        if ($columnCount !== $dataCount) {
            throw new \UnexpectedValueException(sprintf(
                'Expected %d column(s) but received data with %d column(s)',
                $columnCount,
                $dataCount
            ));
        }

        return array_combine($columns, $data);
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
     * @return int
     */
    public function getTotal()
    {
        return $this->getCsvMeta()->getTotal();
    }

    /**
     * @param string $filename
     *
     * @throws \Spryker\Shared\Library\Reader\Exception\ResourceNotFoundException
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
     * @throws \Spryker\Shared\Library\Reader\Exception\ResourceNotFoundException
     *
     * @return array
     */
    public function read()
    {
        $data = $this->getFile()->fgetcsv();
        if (empty($data)) {
            throw new ResourceNotFoundException(sprintf(
                'Malformed data at line %d in %s',
                $this->readIndex,
                $this->csvFilename
            ));
        }

        $data = $this->composeItem($this->getCsvMeta()->getColumns(), $data);
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
     * @return void
     */
    public function rewind($skipColumns = true)
    {
        $this->csvFile->fseek(0);

        if ($skipColumns) {
            $this->getFile()->fseek($this->getCsvMeta()->getColumnsOffset());
            return;
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

        $csvFile->rewind();
        while (!$csvFile->eof()) {
            $data[] = $this->composeItem($csvMeta->getColumns(), $csvFile->fgetcsv());
        }

        return $data;
    }

}
