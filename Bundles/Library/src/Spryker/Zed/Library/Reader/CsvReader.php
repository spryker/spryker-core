<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Library\Reader;

use Pyz\Zed\Installer\Business\Exception\DataFileNotFoundException;
use SplFileObject;

class CsvReader implements CsvReaderInterface
{

    /**
     * @var string
     */
    protected $dataDirectory;

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
     * @param string $dataDirectory
     */
    public function __construct($dataDirectory, $lineBreaker="\n")
    {
        $this->dataDirectory = $dataDirectory;
        $this->lineBreaker = $lineBreaker;
    }

    /**
     * @param string $filename
     *
     * @throws \Pyz\Zed\Installer\Business\Exception\DataFileNotFoundException
     *
     * @return $this
     */
    public function read($filename)
    {
        $filename = $this->dataDirectory . DIRECTORY_SEPARATOR . $filename;

        if (!is_file($filename)) {
            throw new DataFileNotFoundException($filename);
        }

        $this->csvFile = new SplFileObject($filename);
        $this->csvFile->setCsvControl(',', '"');
        $this->csvFile->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY);

        $this->setupColumns();

        $this->total = null;

        return $this;
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
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
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
     * @return \SplFileObject
     */
    public function getFile()
    {
        return $this->csvFile;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $data = [];
        $this->csvFile->rewind();

        while (!$this->csvFile->eof()) {
            $line = $this->csvFile->fgetcsv();
            $data[] = array_combine(array_values($this->columns), array_values($line));
        }

        return $data;
    }

}
