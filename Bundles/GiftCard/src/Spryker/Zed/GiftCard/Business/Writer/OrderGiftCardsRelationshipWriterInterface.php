<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\Writer;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

interface OrderGiftCardsRelationshipWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderGiftCards(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void;
}
