<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCode\Business\Operation;

use Generated\Shared\Transfer\CartCodeResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteOperationCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer|null
     */
    public function checkLockedQuoteResponse(QuoteTransfer $quoteTransfer): ?CartCodeResponseTransfer;
}
