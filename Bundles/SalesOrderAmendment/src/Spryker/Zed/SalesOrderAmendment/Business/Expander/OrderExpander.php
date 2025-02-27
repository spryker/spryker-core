<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Expander;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentConditionsTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentCriteriaTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Spryker\Zed\SalesOrderAmendment\Business\Reader\SalesOrderAmendmentReaderInterface;

class OrderExpander implements OrderExpanderInterface
{
    /**
     * @uses \Orm\Zed\SalesOrderAmendment\Persistence\Map\SpySalesOrderAmendmentTableMap::COL_CREATED_AT
     *
     * @var string
     */
    protected const COL_CREATED_AT = 'spy_sales_order_amendment.created_at';

    /**
     * @param \Spryker\Zed\SalesOrderAmendment\Business\Reader\SalesOrderAmendmentReaderInterface $salesOrderAmendmentReader
     */
    public function __construct(protected SalesOrderAmendmentReaderInterface $salesOrderAmendmentReader)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expand(OrderTransfer $orderTransfer): OrderTransfer
    {
        $salesOrderAmendmentConditionsTransfer = (new SalesOrderAmendmentConditionsTransfer())
            ->addOriginalOrderReference($orderTransfer->getOrderReferenceOrFail());
        $sortTransfer = (new SortTransfer())
            ->setField(static::COL_CREATED_AT)
            ->setIsAscending(false);
        $salesOrderAmendmentCriteriaTransfer = (new SalesOrderAmendmentCriteriaTransfer())
            ->setSalesOrderAmendmentConditions($salesOrderAmendmentConditionsTransfer)
            ->addSort($sortTransfer);

        $salesOrderAmendmentCollectionTransfer = $this->salesOrderAmendmentReader->getSalesOrderAmendmentCollection($salesOrderAmendmentCriteriaTransfer);

        return $orderTransfer->setSalesOrderAmendment(
            $salesOrderAmendmentCollectionTransfer->getSalesOrderAmendments()
                ->getIterator()
                ->current(),
        );
    }
}
