<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Updater;

use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionRequestTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionResponseTransfer;

interface SalesOrderAmendmentQuoteUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionRequestTransfer $salesOrderAmendmentQuoteCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionResponseTransfer
     */
    public function updateSalesOrderAmendmentQuoteCollection(
        SalesOrderAmendmentQuoteCollectionRequestTransfer $salesOrderAmendmentQuoteCollectionRequestTransfer
    ): SalesOrderAmendmentQuoteCollectionResponseTransfer;
}
