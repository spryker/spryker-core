<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart\CartOperation;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface CartDeleteCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $currentQuoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function isDeleteCartAllowed(QuoteTransfer $currentQuoteTransfer, CustomerTransfer $customerTransfer): bool;
}
