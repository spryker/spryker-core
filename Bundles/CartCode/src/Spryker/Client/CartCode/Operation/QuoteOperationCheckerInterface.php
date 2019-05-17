<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartCode\Operation;

use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteOperationCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer|null
     */
    public function checkLockedQuoteResponse(QuoteTransfer $quoteTransfer): ?CartCodeOperationResultTransfer;
}
