<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business\ThresholdMessenger;

use Generated\Shared\Transfer\QuoteTransfer;

interface ThresholdMessengerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addSalesOrderThresholdMessages(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
