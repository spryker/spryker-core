<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\Shipment;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @deprecated Use {@link \Spryker\Zed\GiftCard\Business\ShipmentGroup\ShipmentGroupMethodFilterInterface} instead.
 */
interface ShipmentMethodFilterInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ShipmentMethodTransfer> $shipmentMethods
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ShipmentMethodTransfer>
     */
    public function filterShipmentMethods(ArrayObject $shipmentMethods, QuoteTransfer $quoteTransfer);
}
