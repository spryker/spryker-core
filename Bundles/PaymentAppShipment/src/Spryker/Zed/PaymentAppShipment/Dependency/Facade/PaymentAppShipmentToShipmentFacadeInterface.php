<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentAppShipment\Dependency\Facade;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;

interface PaymentAppShipmentToShipmentFacadeInterface
{
    /**
     * @param string $shipmentMethodKey
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findShipmentMethodByKey(string $shipmentMethodKey): ?ShipmentMethodTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteWithShipmentGroups(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
