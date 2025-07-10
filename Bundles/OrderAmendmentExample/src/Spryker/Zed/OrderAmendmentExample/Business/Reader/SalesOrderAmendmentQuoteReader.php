<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderAmendmentExample\Business\Reader;

use Generated\Shared\Transfer\SalesOrderAmendmentQuoteConditionsTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer;
use Spryker\Zed\OrderAmendmentExample\Dependency\Facade\OrderAmendmentExampleToSalesOrderAmendmentFacadeInterface;

class SalesOrderAmendmentQuoteReader implements SalesOrderAmendmentQuoteReaderInterface
{
    /**
     * @param \Spryker\Zed\OrderAmendmentExample\Dependency\Facade\OrderAmendmentExampleToSalesOrderAmendmentFacadeInterface $salesOrderAmendmentFacade
     */
    public function __construct(
        protected OrderAmendmentExampleToSalesOrderAmendmentFacadeInterface $salesOrderAmendmentFacade
    ) {
    }

    /**
     * @param string $orderReference
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer|null
     */
    public function findSalesOrderAmendmentQuoteByOrderReference(string $orderReference): ?SalesOrderAmendmentQuoteTransfer
    {
        $salesOrderAmendmentQuoteCollectionTransfer = $this->salesOrderAmendmentFacade->getSalesOrderAmendmentQuoteCollection(
            (new SalesOrderAmendmentQuoteCriteriaTransfer())->setSalesOrderAmendmentQuoteConditions(
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
