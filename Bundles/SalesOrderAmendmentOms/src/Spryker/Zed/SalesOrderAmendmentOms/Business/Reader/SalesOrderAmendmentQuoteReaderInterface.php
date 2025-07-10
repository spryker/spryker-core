<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Business\Reader;

use Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer;

interface SalesOrderAmendmentQuoteReaderInterface
{
    /**
     * @param string $orderReference
     * @param bool $withExpanderPlugins
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer|null
     */
    public function findSalesOrderAmendmentQuoteByOrderReference(
        string $orderReference,
        bool $withExpanderPlugins = false
    ): ?SalesOrderAmendmentQuoteTransfer;
}
