<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataFeed\Persistence;

use Generated\Shared\Transfer\DataFeedConditionTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class DataFeedPager
{

    /**
     * @var ModelCriteria
     */
    protected $query;

    /**
     * @var int
     */
    protected $limit = 0;

    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * DataFeedPager constructor.
     *
     * @param ModelCriteria $query
     * @param DataFeedConditionTransfer $dataFeedConditionTransfer
     *
     */
    public function __construct(ModelCriteria $query, DataFeedConditionTransfer $dataFeedConditionTransfer)
    {
        $this->query = $query;
        $pagination = $dataFeedConditionTransfer->getPagination();

        if ($pagination !== null) {
            $this->setLimit($pagination->getLimit());
            $this->setOffset($pagination->getOffset());
        }
    }

    /**
     * @return array|mixed|\Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function getResults()
    {
        $queryResult = $this->query
            ->find()
            ->toArray();

        if ($this->limit > 0) {
            return array_slice($queryResult, $this->offset, $this->limit);
        }

        return $queryResult;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        if ($limit !== null && $limit > 0) {
            $this->limit = $limit;
        }
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     */
    public function setOffset($offset)
    {
        if ($offset !== null && $offset > 0) {
            $this->offset = $offset;
        }
    }

}