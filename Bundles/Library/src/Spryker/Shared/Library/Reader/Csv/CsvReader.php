<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Reader\Csv;

use SplFileObject;

class CsvReader implements CsvReaderInterface
{

    /**
     * @var
     */
    protected $lineBreaker = "\n";

    /**
     * @var array
     */
    protected $columns = [];

    /**
     * @var int
     */
    protected $total;

    /**
     * @var int
     */
    protected $readIndex = 1;

    /**
     * @var \SplFileObject
     */
    protected $csvFile;

    /**
     * @var bool
     */
    protected $isValidated;


    /**
     * @param string $lineBreaker
     */
    public function __construct($lineBreaker = "\n")
    {
        $this->lineBreaker = $lineBreaker;
    }

    /**
     * @return void
     */
    protected function setupColumns()
    {
        $this->getFile()->fseek(0);

        while (!$this->getFile()->eof()) {
            $this->columns = $this->getFile()->fgetcsv();
            break;
        }
    }

    /**
     * @throws \LogicException
     *
     * @return void
     */
    protected function validate()
    {
        if (!$this->isLoaded()) {
            throw new \LogicException('No CSV file has been loaded');
        }

        $this->isValidated = true;
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
        $this->validate();

        return $this->columns;
    }

    /**
     * @return \SplFileObject
     *
     * @throws \LogicException
     */
    public function getFile()
    {
        if (!$this->isValidated) {
            $this->validate();
        }

        return $this->csvFile;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        if ($this->total === null) {
            $this->getFile()->rewind();

            $lines = 1;
            while (!$this->getFile()->eof()) {
                $lines += substr_count($this->getFile()->fread(8192), $this->lineBreaker);
            }

            $this->total = $lines;
        }

        return $this->total;
    }

    /**
     * @return bool
     */
    public function isLoaded()
    {
        return $this->csvFile !== null && $this->csvFile->isReadable();
    }

    /**
     * @param string $filename
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function load($filename)
    {
        if (!is_readable($filename)) {
            throw new \InvalidArgumentException(sprintf(
                'Could not open csv file "%s"',
                $filename
            ));
        }

        $this->loadCsvFile($filename);

        $this->total = null;
        $this->readIndex = 1;
        $this->isValidated = false;

        $this->setupColumns();

        return $this;
    }

    /**
     * @param string $filename
     *
     * @return void
     */
    protected function loadCsvFile($filename)
    {
        $this->csvFile = new SplFileObject($filename);
        $this->csvFile->setCsvControl(',', '"');
        $this->csvFile->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);
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
            throw new \UnexpectedValueException('Malformed data at line ' . $this->readIndex);
        }

        $data = $this->composeItem($this->columns, $data);
        $this->readIndex++;

        return $data;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $data = [];
        $csvFile = clone $this->getFile();
        $csvFile->rewind();

        while (!$csvFile->eof()) {
            $data[] = $this->composeItem($this->columns, $csvFile->fgetcsv());
        }

        return $data;
    }

}
