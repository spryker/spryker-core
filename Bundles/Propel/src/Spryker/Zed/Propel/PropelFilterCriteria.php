<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel;

use Generated\Shared\Transfer\FilterTransfer;
use Propel\Runtime\ActiveQuery\Criteria;

class PropelFilterCriteria implements PropelFilterCriteriaInterface
{
    /**
     * @var string
     */
    protected const PATTERN_VALID_COLUMN_NAME = '/^[a-zA-Z0-9_.]+$/';

    /**
     * @var \Generated\Shared\Transfer\FilterTransfer
     */
    protected $filterTransfer;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     */
    public function __construct(FilterTransfer $filterTransfer)
    {
        $this->filterTransfer = $filterTransfer;
    }

    /**
     * @inheritDoc
     */
    public function getFilterTransfer()
    {
        return $this->filterTransfer;
    }

    /**
     * @inheritDoc
     */
    public function setFilterTransfer(FilterTransfer $filterTransfer)
    {
        $this->filterTransfer = $filterTransfer;
    }

    /**
     * @inheritDoc
     */
    public function toCriteria()
    {
        $criteria = new Criteria();
        $criteria = $this->addPaginationToCriteria($criteria);
        $criteria = $this->addSortingToCriteria($criteria);

        return $criteria;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Criteria $criteria
     *
     * @return \Propel\Runtime\ActiveQuery\Criteria
     */
    protected function addPaginationToCriteria(Criteria $criteria): Criteria
    {
        $limit = $this->filterTransfer->getLimit();
        if ($limit !== null) {
            $criteria->setLimit($limit);
        }

        $offset = $this->filterTransfer->getOffset();
        if ($offset !== null) {
            $criteria->setOffset($offset);
        }

        return $criteria;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Criteria $criteria
     *
     * @return \Propel\Runtime\ActiveQuery\Criteria
     */
    protected function addSortingToCriteria(Criteria $criteria): Criteria
    {
        $orderByColumnName = $this->filterTransfer->getOrderBy();
        if ($orderByColumnName === null || !$this->isColumnNameValid($orderByColumnName)) {
            return $criteria;
        }

        $orderDirection = $this->filterTransfer->getOrderDirection();
        if ($orderDirection === Criteria::ASC) {
            $criteria->addAscendingOrderByColumn($orderByColumnName);
        }

        if ($orderDirection === Criteria::DESC) {
            $criteria->addDescendingOrderByColumn($orderByColumnName);
        }

        return $criteria;
    }

    /**
     * @param string $orderByColumnName
     *
     * @return bool
     */
    protected function isColumnNameValid(string $orderByColumnName): bool
    {
        return (bool)preg_match(static::PATTERN_VALID_COLUMN_NAME, $orderByColumnName);
    }
}
