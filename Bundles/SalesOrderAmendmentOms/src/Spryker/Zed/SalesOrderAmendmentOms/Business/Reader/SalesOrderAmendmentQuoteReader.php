<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Business\Reader;

use Generated\Shared\Transfer\SalesOrderAmendmentQuoteConditionsTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface;

class SalesOrderAmendmentQuoteReader implements SalesOrderAmendmentQuoteReaderInterface
{
    /**
     * @param \Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface $salesOrderAmendmentFacade
     */
    public function __construct(
        protected SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface $salesOrderAmendmentFacade
    ) {
    }

    /**
     * @param string $orderReference
     * @param bool $withExpanderPlugins
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer|null
     */
    public function findSalesOrderAmendmentQuoteByOrderReference(
        string $orderReference,
        bool $withExpanderPlugins = false
    ): ?SalesOrderAmendmentQuoteTransfer {
        $salesOrderAmendmentQuoteCollectionTransfer = $this->salesOrderAmendmentFacade->getSalesOrderAmendmentQuoteCollection(
            (new SalesOrderAmendmentQuoteCriteriaTransfer())
                ->setWithExpanderPlugins($withExpanderPlugins)
                ->setSalesOrderAmendmentQuoteConditions(
                    (new SalesOrderAmendmentQuoteConditionsTransfer())->addAmendmentOrderReference(
                        $orderReference,
                    ),
                ),
        );

        return $salesOrderAmendmentQuoteCollectionTransfer->getSalesOrderAmendmentQuotes()
            ->getIterator()
            ->current();
    }
}
