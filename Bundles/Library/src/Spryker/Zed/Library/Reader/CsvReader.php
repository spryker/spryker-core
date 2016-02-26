<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Library\Reader;

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
        return array_combine(array_values($this->columns), array_values($data));
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
        $this->csvFile->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY);

        $this->setupColumns();

        $this->total = null;

        return $this;
    }

    /**
     * @return array
     */
    public function read()
    {
        return $this->composeItem($this->getFile()->fgetcsv());
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $data = [];
        $this->csvFile->rewind();

        while (!$this->csvFile->eof()) {
            $data[] = $this->read();
        }

        return $data;
    }

}
