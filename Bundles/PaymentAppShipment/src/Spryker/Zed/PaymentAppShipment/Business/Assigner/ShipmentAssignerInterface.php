<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentAppShipment\Business\Assigner;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

interface ShipmentAssignerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function assignShipmentToQuoteItems(
        QuoteTransfer $quoteTransfer,
        ShipmentTransfer $shipmentTransfer
    ): QuoteTransfer;
}
