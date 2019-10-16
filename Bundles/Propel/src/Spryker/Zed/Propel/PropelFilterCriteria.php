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

        if ($this->filterTransfer->getLimit() !== null) {
            $criteria->setLimit($this->filterTransfer->getLimit());
        }

        if ($this->filterTransfer->getOffset() !== null) {
            $criteria->setOffset($this->filterTransfer->getOffset());
        }

        if ($this->filterTransfer->getOrderBy() !== null) {
            if ($this->filterTransfer->getOrderDirection() === 'ASC') {
                $criteria->addAscendingOrderByColumn($this->filterTransfer->getOrderBy());
            } elseif ($this->filterTransfer->getOrderDirection() === 'DESC') {
                $criteria->addDescendingOrderByColumn($this->filterTransfer->getOrderBy());
            }
        }

        return $criteria;
    }
}
