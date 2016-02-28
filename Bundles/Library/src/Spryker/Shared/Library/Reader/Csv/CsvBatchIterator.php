<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Reader\Csv;

use Spryker\Shared\Library\Reader\CountableIteratorInterface;

class CsvBatchIterator implements CountableIteratorInterface
{

    /**
     * @var \Spryker\Shared\Library\Reader\Csv\CsvReaderInterface
     */
    protected $csvReader;

    /**
     * @var
     */
    protected $csvFilename;

    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @var int
     */
    protected $chunkSize = 10;

    /**
     * @var bool
     */
    protected $isValid = true;

    /**
     * @var array
     */
    protected $batchData = [];

    /**
     * @param string $filename
     * @param int $chunkSize
     */
    public function __construct($filename, $chunkSize = 10)
    {
        $this->csvFilename = $filename;
        $this->chunkSize = $chunkSize;
    }

    /**
     * @return \Spryker\Shared\Library\Reader\Csv\CsvReaderInterface
     */
    protected function getCsvReader()
    {
        if ($this->csvReader === null) {
            $this->csvReader = new CsvReader();
            $this->csvReader->load($this->csvFilename);
        }

        return $this->csvReader;
    }

    /**
     * @return void
     */
    protected function loadChunk()
    {
        while (!$this->getCsvReader()->eof()) {
            $this->batchData[] = $this->getCsvReader()->read();
            $this->offset++;

            if (count($this->batchData) >= $this->chunkSize) {
                break;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->batchData;
    }

    /**
     * {@inheritdoc}
     * x
     * @return void
     */
    public function next()
    {
        $this->batchData = [];
        $this->loadChunk();
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->offset;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return !empty($this->batchData);
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function rewind()
    {
        $this->offset = 0;
        $this->getCsvReader()->getFile()->rewind();
        $this->loadChunk();
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->getCsvReader()->getTotal();
    }

}
