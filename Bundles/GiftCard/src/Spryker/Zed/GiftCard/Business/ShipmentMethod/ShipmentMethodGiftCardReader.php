<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\ShipmentMethod;

use Spryker\Zed\GiftCard\GiftCardConfig;

/**
 * @deprecated Added for BC reasons, will be removed in next major release. Use GiftCardConfig::getGiftCardOnlyShipmentMethods() instead.
 */
class ShipmentMethodGiftCardReader implements ShipmentMethodGiftCardReaderInterface
{
    protected const NO_SHIPMENT_METHOD = 'NO_SHIPMENT_METHOD';

    /**
     * @var \Spryker\Zed\GiftCard\GiftCardConfig
     */
    protected $giftCardConfig;

    /**
     * @param \Spryker\Zed\GiftCard\GiftCardConfig $giftCardConfig
     */
    public function __construct(GiftCardConfig $giftCardConfig)
    {
        $this->giftCardConfig = $giftCardConfig;
    }

    /**
     * @return string[]
     */
    public function getGiftCardOnlyShipmentMethods(): array
    {
        $giftCardOnlyShipmentMethods = $this->giftCardConfig->getGiftCardOnlyShipmentMethods();
        if (count($giftCardOnlyShipmentMethods) === 0) {
            return [static::NO_SHIPMENT_METHOD];
        }

        return $giftCardOnlyShipmentMethods;
    }
}
