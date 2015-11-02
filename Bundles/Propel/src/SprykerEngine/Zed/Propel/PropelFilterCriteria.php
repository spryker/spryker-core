<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel;

use Propel\Runtime\ActiveQuery\Criteria;
use Generated\Shared\Transfer\FilterTransfer;

class PropelFilterCriteria implements PropelFilterCriteriaInterface
{

    /**
     * @var FilterTransfer
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

        if (null !== $this->filterTransfer->getLimit()) {
            $criteria->setLimit($this->filterTransfer->getLimit());
        }

        if (null !== $this->filterTransfer->getOffset()) {
            $criteria->setOffset($this->filterTransfer->getOffset());
        }

        if (null !== $this->filterTransfer->getOrderBy()) {
            if ('ASC' === $this->filterTransfer->getOrderDirection()) {
                $criteria->addAscendingOrderByColumn($this->filterTransfer->getOrderBy());
            } elseif ('DESC' === $this->filterTransfer->getOrderDirection()) {
                $criteria->addDescendingOrderByColumn($this->filterTransfer->getOrderBy());
            }
        }

        return $criteria;
    }

}
