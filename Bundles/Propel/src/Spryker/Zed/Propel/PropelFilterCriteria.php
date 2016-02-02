<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Propel;

use Propel\Runtime\ActiveQuery\Criteria;
use Generated\Shared\Transfer\FilterTransfer;

class PropelFilterCriteria implements PropelFilterCriteriaInterface
{

    /**
     * @var \Generated\Shared\Transfer\FilterTransfer
     */
    protected $filterTransfer;

    public function __construct(FilterTransfer $filterTransfer)
    {
        $this->filterTransfer = $filterTransfer;
    }

    /**
     * @inheritdoc
     */
    public function getFilterTransfer()
    {
        return $this->filterTransfer;
    }

    /**
     * @inheritdoc
     *
     * @return void
     */
    public function setFilterTransfer(FilterTransfer $filterTransfer)
    {
        $this->filterTransfer = $filterTransfer;
    }

    /**
     * @inheritdoc
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
