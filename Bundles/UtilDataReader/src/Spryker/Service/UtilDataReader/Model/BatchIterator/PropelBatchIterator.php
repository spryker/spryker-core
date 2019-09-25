<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilDataReader\Model\BatchIterator;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class PropelBatchIterator implements CountableIteratorInterface
{
    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @var int
     */
    protected $chunkSize = 100;

    /**
     * @var \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected $query;

    /**
     * @var array
     */
    protected $currentDataSet = [];

    /**
     * @var string|null
     */
    protected $orderBy;

    /**
     * @var string|null
     */
    protected $orderByDirection;

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param int $chunkSize
     * @param string|null $orderBy
     * @param string|null $orderByDirection
     */
    public function __construct(ModelCriteria $query, $chunkSize = 100, $orderBy = null, $orderByDirection = null)
    {
        $this->query = $query;
        $this->chunkSize = $chunkSize;
        $this->orderBy = $orderBy;
        $this->orderByDirection = $orderByDirection;
    }

    /**
     * @return void
     */
    protected function loadChunk()
    {
        $this->query->setOffset($this->offset);
        $this->query->setLimit($this->chunkSize);

        if ($this->orderBy) {
            if (!$this->orderByDirection) {
                $this->orderByDirection = Criteria::ASC;
            }

            $this->query->orderBy($this->orderBy, $this->orderByDirection);
        }

        $this->currentDataSet = $this->query->find();

        $this->offset += $this->chunkSize;
    }

    /**
     * @inheritdoc
     */
    public function current()
    {
        return $this->currentDataSet;
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        $this->loadChunk();
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return $this->offset;
    }

    /**
     * @inheritdoc
     */
    public function valid()
    {
        return count($this->currentDataSet);
    }

    /**
     * @inheritdoc
     *
     * @return void
     */
    public function rewind()
    {
        $this->offset = 0;
        $this->loadChunk();
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        $query = clone $this->query;
        $query->setLimit(-1);
        $query->setOffset(-1);

        return $query->count();
    }
}
