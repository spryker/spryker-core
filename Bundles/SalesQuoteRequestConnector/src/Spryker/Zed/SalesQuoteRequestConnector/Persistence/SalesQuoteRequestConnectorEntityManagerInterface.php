<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuoteRequestConnector\Persistence;

interface SalesQuoteRequestConnectorEntityManagerInterface
{
    /**
     * @param int $idSalesOrder
     * @param string $quoteRequestVersionReference
     *
     * @return void
     */
    public function saveOrderQuoteRequestVersionReference(int $idSalesOrder, string $quoteRequestVersionReference): void;
}
