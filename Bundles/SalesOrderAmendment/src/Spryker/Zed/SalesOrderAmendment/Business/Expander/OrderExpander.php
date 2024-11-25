<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Expander;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentConditionsTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentCriteriaTransfer;
use Spryker\Zed\SalesOrderAmendment\Business\Reader\SalesOrderAmendmentReaderInterface;

class OrderExpander implements OrderExpanderInterface
{
    /**
     * @var \Spryker\Zed\SalesOrderAmendment\Business\Reader\SalesOrderAmendmentReaderInterface
     */
    protected SalesOrderAmendmentReaderInterface $salesOrderAmendmentReader;

    /**
     * @param \Spryker\Zed\SalesOrderAmendment\Business\Reader\SalesOrderAmendmentReaderInterface $salesOrderAmendmentReader
     */
    public function __construct(SalesOrderAmendmentReaderInterface $salesOrderAmendmentReader)
    {
        $this->salesOrderAmendmentReader = $salesOrderAmendmentReader;
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
        $salesOrderAmendmentCriteriaTransfer = (new SalesOrderAmendmentCriteriaTransfer())
            ->setSalesOrderAmendmentConditions($salesOrderAmendmentConditionsTransfer);

        $salesOrderAmendmentCollectionTransfer = $this->salesOrderAmendmentReader->getSalesOrderAmendmentCollection($salesOrderAmendmentCriteriaTransfer);

        return $orderTransfer->setSalesOrderAmendment(
            $salesOrderAmendmentCollectionTransfer->getSalesOrderAmendments()
                ->getIterator()
                ->current(),
        );
    }
}
