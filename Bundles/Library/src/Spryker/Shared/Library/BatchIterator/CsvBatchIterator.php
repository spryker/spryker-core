<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\BatchIterator;

use Spryker\Shared\Library\Reader\Csv\CsvReader;

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
    protected $chunkSize = 100;

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
        $this->batchData = [];

        $batchSize = $this->offset + $this->chunkSize;
        if ($batchSize > $this->getCsvReader()->getTotal()) {
            $batchSize = $this->getCsvReader()->getTotal();
        }

        while ($this->getCsvReader()->valid()) {
            $this->batchData[] = $this->getCsvReader()->read();
            $this->offset++;

            if (count($this->batchData) >= $batchSize) {
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
        $this->getCsvReader()->rewind();
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
