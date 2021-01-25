<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApprovalShipmentConnector\Checker;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteShipmentCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function checkQuoteShipment(QuoteTransfer $quoteTransfer): bool;
}
