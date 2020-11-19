<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardsRestApi\Business;

use Generated\Shared\Transfer\QuoteTransfer;

interface GiftCardsRestApiFacadeInterface
{
    /**
     * Specification:
     * - Adds default shipment for gift cards.
     * - Sets `NoShipment` selection for gift cards.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addDefaultShipmentForGiftCards(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
