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
        $this->csvFile->fseek(0);

        while (!$this->csvFile->eof()) {
            $this->columns = $this->csvFile->fgetcsv();
            break;
        }
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function composeItem(array $data)
    {
        $data = array_values($data);
        $columns = array_values($this->columns);

        if (count($data) !== count($columns)) {
            throw new \UnexpectedValueException('Expected "'. count($columns) . '" column(s) but received data with "'.count($data).'" column(s)');
        }

        return array_combine($columns, $data);
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return \SplFileObject
     */
    public function getFile()
    {
        return $this->csvFile;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        if ($this->total === null) {
            $this->csvFile->rewind();

            $lines = 1;
            while (!$this->csvFile->eof()) {
                $lines += substr_count($this->csvFile->fread(8192), $this->lineBreaker);
            }

            $this->total = $lines;
        }

        return $this->total;
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
        if (!is_file($filename)) {
            throw new \InvalidArgumentException($filename);
        }

        $this->csvFile = new SplFileObject($filename);
        $this->csvFile->setCsvControl(',', '"');
        $this->csvFile->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);

        $this->setupColumns();

        $this->total = null;

        $this->readIndex = 1;

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
            throw new \UnexpectedValueException('Malformed data at line ' . $this->readIndex);
        }

        $data = $this->composeItem($data);
        $this->readIndex++;

        return $data;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $data = [];
        $csvFile = clone $this->csvFile;
        $csvFile->rewind();

        while (!$csvFile->eof()) {
            $data[] = $this->composeItem($csvFile->fgetcsv());
        }

        return $data;
    }

}
