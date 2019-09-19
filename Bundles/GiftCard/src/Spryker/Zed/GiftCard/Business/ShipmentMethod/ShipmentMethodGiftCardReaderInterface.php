<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\ShipmentMethod;

/**
 * @deprecated Added for BC reasons, will be removed in next major release. Use GiftCardConfig::getGiftCardOnlyShipmentMethods() instead.
 */
interface ShipmentMethodGiftCardReaderInterface
{
    /**
     * @return string[]
     */
    public function getGiftCardOnlyShipmentMethods(): array;
}
