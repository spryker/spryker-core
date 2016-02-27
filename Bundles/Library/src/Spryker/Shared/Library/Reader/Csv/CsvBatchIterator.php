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
        $batchSize = $this->offset + $this->chunkSize;
        if ($batchSize > $this->getCsvReader()->getTotal()) {
            $batchSize = $this->getCsvReader()->getTotal();
        }

        $batchData = [];
        while ($this->offset < $batchSize) {
            $batchData[] = $this->getCsvReader()->read();
            $this->offset++;
        }

        $this->batchData = $batchData;
        $this->isValid = is_array($this->batchData) && !empty($this->batchData);

        if ($this->offset > $this->getCsvReader()->getTotal()) {
            $this->offset = $this->getCsvReader()->getTotal();
        }
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
        return $this->isValid;
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function rewind()
    {
        $this->offset = 0;
        //$this->next(); TODO check if that's needed
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->getCsvReader()->getTotal();
    }

}
