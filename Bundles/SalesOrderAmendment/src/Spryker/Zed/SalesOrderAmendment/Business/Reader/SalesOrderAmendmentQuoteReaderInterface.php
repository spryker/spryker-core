<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Reader;

use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer;

interface SalesOrderAmendmentQuoteReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCriteriaTransfer $salesOrderAmendmentQuoteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionTransfer
     */
    public function getSalesOrderAmendmentQuoteCollection(
        SalesOrderAmendmentQuoteCriteriaTransfer $salesOrderAmendmentQuoteCriteriaTransfer
    ): SalesOrderAmendmentQuoteCollectionTransfer;

    /**
     * @param string $orderReference
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer|null
     */
    public function findSalesOrderAmendmentQuoteByOrderReference(string $orderReference): ?SalesOrderAmendmentQuoteTransfer;
}
