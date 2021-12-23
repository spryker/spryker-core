<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business\Expander;

use Generated\Shared\Transfer\QuoteTransfer;

interface SalesOrderThresholdValueExpanderInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\SalesOrderThresholdValueTransfer> $salesOrderThresholdValueTransfers
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<\Generated\Shared\Transfer\SalesOrderThresholdValueTransfer>
     */
    public function expandSalesOrderThresholdValues(array $salesOrderThresholdValueTransfers, QuoteTransfer $quoteTransfer): array;
}
