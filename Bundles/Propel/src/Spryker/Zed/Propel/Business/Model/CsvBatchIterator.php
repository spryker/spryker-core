<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model;

use Spryker\Zed\Library\Reader\CsvReaderInterface;

class CsvBatchIterator implements CountableIteratorInterface
{

    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @var int
     */
    protected $chunkSize = 10;

    /**
     * @var \Spryker\Zed\Library\Reader\CsvReaderInterface
     */
    protected $csvReader;

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
     * @param \Spryker\Zed\Library\Reader\CsvReaderInterface $csvFile
     * @param int $chunkSize
     */
    public function __construct(CsvReaderInterface $csvFile, $chunkSize = 100)
    {
        $this->csvReader = $csvFile;
        $this->csvReader->getFile()->flock(LOCK_EX); //make sure nobody else will rewind
        $this->chunkSize = $chunkSize;
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
     *
     * @return void
     */
    public function next()
    {
        $chunkData = [];
        $batchSize = $this->offset + $this->chunkSize;

        while ($this->currentKey < $batchSize) {
            $chunkData[] = $this->csvReader->read();
            $this->currentKey++;
        }

        $this->currentDataSet = $chunkData;
        $this->isValid = is_array($this->currentDataSet) && !empty($this->currentDataSet);
        $this->offset += $this->chunkSize;
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
        return $this->csvReader->getTotal();
    }

    /**
     *
     */
    public function __destruct()
    {
        $this->csvReader->getFile()->flock(LOCK_UN);
    }

}
