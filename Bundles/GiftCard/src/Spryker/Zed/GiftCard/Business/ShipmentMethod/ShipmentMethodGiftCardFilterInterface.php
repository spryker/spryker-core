<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\ShipmentMethod;

use ArrayObject;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;

interface ShipmentMethodGiftCardFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[]
     */
    public function filterNonGiftCardShipmentMethods(ShipmentMethodsTransfer $shipmentMethodsTransfer): ArrayObject;

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[]
     */
    public function filterGiftCardOnlyShipmentMethods(ShipmentMethodsTransfer $shipmentMethodsTransfer): ArrayObject;
}
