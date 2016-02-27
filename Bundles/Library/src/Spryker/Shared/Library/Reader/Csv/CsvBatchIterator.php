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
     * @var int
     */
    protected $currentKey = 0;

    /**
     * @var bool
     */
    protected $isValid = true;

    /**
     * @var array
     */
    protected $currentDataSet = [];

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
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->currentDataSet;
    }

    /**
     * {@inheritdoc}
     * x
     * @return void
     */
    public function next()
    {
        $chunkData = [];
        $batchSize = $this->offset + $this->chunkSize;

        if ($batchSize > $this->getCsvReader()->getTotal()) {
            $batchSize = $this->getCsvReader()->getTotal();
        }

        while ($this->currentKey < $batchSize) {
            $chunkData[] = $this->getCsvReader()->read();
            $this->currentKey++;

            if ($this->currentKey >= $this->getCsvReader()->getTotal()) {
                break;
            }
        }

        $this->currentDataSet = $chunkData;
        $this->isValid = is_array($this->currentDataSet) && !empty($this->currentDataSet);
        $this->offset += $this->chunkSize;

        if ($this->offset > $this->getCsvReader()->getTotal()) {
            $this->offset = $this->getCsvReader()->getTotal();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->currentKey;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return $this->isValid;
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function rewind()
    {
        $this->currentKey = 0;
        $this->offset = 0;
        $this->next();
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->getCsvReader()->getTotal();
    }

}
