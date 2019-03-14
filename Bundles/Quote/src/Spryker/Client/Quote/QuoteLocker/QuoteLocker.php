<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote\QuoteLocker;

use Generated\Shared\Transfer\QuoteTransfer;

class QuoteLocker implements QuoteLockerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function lock(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $quoteTransfer->setIsLocked(true);
    }
}
