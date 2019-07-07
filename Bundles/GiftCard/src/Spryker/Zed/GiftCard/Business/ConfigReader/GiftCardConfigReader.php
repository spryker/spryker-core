<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\ConfigReader;

use Spryker\Zed\GiftCard\GiftCardConfig;

class GiftCardConfigReader implements GiftCardConfigReaderInterface
{
    public const NO_SHIPMENT_METHOD = 'NO_SHIPMENT_METHOD';

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
        return $this->giftCardConfig->getGiftCardOnlyShipmentMethods();
    }

    /**
     * @deprecated Added for BC reasons, will be removed in next major release. Use getGiftCardOnlyShipmentMethods() instead.
     *
     * @return string[]
     */
    public function getGiftCardOnlyShipmentMethodsWithBC(): array
    {
        $giftCardOnlyShipmentMethods = $this->getGiftCardOnlyShipmentMethods();
        if (count($giftCardOnlyShipmentMethods) === 0) {
            return [static::NO_SHIPMENT_METHOD];
        }

        return $giftCardOnlyShipmentMethods;
    }
}
