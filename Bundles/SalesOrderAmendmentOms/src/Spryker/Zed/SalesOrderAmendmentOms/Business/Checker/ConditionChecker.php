<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Business\Checker;

use Spryker\Zed\SalesOrderAmendmentOms\Business\Reader\SalesOrderAmendmentQuoteReaderInterface;

class ConditionChecker implements ConditionCheckerInterface
{
    /**
     * @param \Spryker\Zed\SalesOrderAmendmentOms\Business\Reader\SalesOrderAmendmentQuoteReaderInterface $salesOrderAmendmentQuoteReader
     */
    public function __construct(protected SalesOrderAmendmentQuoteReaderInterface $salesOrderAmendmentQuoteReader)
    {
    }

    /**
     * @param string $orderReference
     *
     * @return bool
     */
    public function isOrderAmendmentDraftSuccessfullyApplied(string $orderReference): bool
    {
        $salesOrderAmendmentQuoteTransfer = $this->salesOrderAmendmentQuoteReader
            ->findSalesOrderAmendmentQuoteByOrderReference($orderReference);

        if (!$salesOrderAmendmentQuoteTransfer) {
            return true;
        }

        $quoteTransfer = $salesOrderAmendmentQuoteTransfer->getQuoteOrFail();

        return $quoteTransfer->getErrors()->count() === 0;
    }
}
