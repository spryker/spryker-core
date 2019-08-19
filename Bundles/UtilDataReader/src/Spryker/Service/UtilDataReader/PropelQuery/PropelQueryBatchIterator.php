<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilDataReader\PropelQuery;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class PropelQueryBatchIterator implements CountableIteratorInterface
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
    public function __construct(ModelCriteria $query, int $chunkSize = 100, ?string $orderBy = null, ?string $orderByDirection = null)
    {
        $this->query = $query;
        $this->chunkSize = $chunkSize;
        $this->orderBy = $orderBy;
        $this->orderByDirection = $orderByDirection;
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
        return count($this->currentDataSet);
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function rewind()
    {
        $this->offset = 0;
        $this->loadChunk();
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        $query = clone $this->query;
        $query->setLimit(-1);
        $query->setOffset(-1);

        return $query->count();
    }

    /**
     * @return void
     */
    protected function loadChunk(): void
    {
        $query = clone $this->query;

        $query->setOffset($this->offset);
        $query->setLimit($this->chunkSize);

        if ($this->orderBy) {
            if (!$this->orderByDirection) {
                $this->orderByDirection = Criteria::ASC;
            }

            $query->orderBy($this->orderBy, $this->orderByDirection);
        }

        $this->currentDataSet = $query->find();

        $this->offset += $this->chunkSize;
    }
}
