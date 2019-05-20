<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\Shipment;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\GiftCard\GiftCardConfig;

class ShipmentMethodFilter implements ShipmentMethodFilterInterface
{
    /**
     * @deprecated Use GiftCardConfig::getGiftCardOnlyShipmentMethods() instead.
     */
    public const NO_SHIPMENT_METHOD = 'No shipment';

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
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethods
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethods
     */
    public function filterShipmentMethods(ArrayObject $shipmentMethods, QuoteTransfer $quoteTransfer)
    {
        if ($this->containsOnlyGiftCardItems($quoteTransfer)) {
            return $this->allowGiftCardOnlyShipmentMethods($shipmentMethods);
        }

        return $this->disallowGiftCardOnlyShipmentMethods($shipmentMethods);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function containsOnlyGiftCardItems(QuoteTransfer $quoteTransfer)
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$this->isGiftCard($itemTransfer)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isGiftCard(ItemTransfer $itemTransfer)
    {
        $metadata = $itemTransfer->getGiftCardMetadata();

        if (!$metadata) {
            return false;
        }

        return $metadata->getIsGiftCard();
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethods $shipmentMethods
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethods
     */
    protected function allowGiftCardOnlyShipmentMethods(ArrayObject $shipmentMethods): ArrayObject
    {
        $result = new ArrayObject();
        $giftCardOnlyShipmentMethods = $this->getGiftCardOnlyShipmentMethods();
        foreach ($shipmentMethods as $shipmentMethod) {
            if (in_array($shipmentMethod->getName(), $giftCardOnlyShipmentMethods)) {
                $result[] = $shipmentMethod;
            }
        }

        return $result;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethods $shipmentMethods
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethods
     */
    protected function disallowGiftCardOnlyShipmentMethods(ArrayObject $shipmentMethods): ArrayObject
    {
        $result = new ArrayObject();
        $giftCardOnlyShipmentMethods = $this->getGiftCardOnlyShipmentMethods();
        foreach ($shipmentMethods as $shipmentMethod) {
            if (!in_array($shipmentMethod->getName(), $giftCardOnlyShipmentMethods)) {
                $result[] = $shipmentMethod;
            }
        }

        return $result;
    }

    /**
     * @deprecated Added for BC reasons, will be removed in next major release. Use GiftCardConfig::getGiftCardOnlyShipmentMethods() instead.
     *
     * @return array
     */
    protected function getGiftCardOnlyShipmentMethods(): array
    {
        $giftCardOnlyShipmentMethods = $this->giftCardConfig->getGiftCardOnlyShipmentMethods();

        if ($giftCardOnlyShipmentMethods) {
            return $giftCardOnlyShipmentMethods;
        }

        return [static::NO_SHIPMENT_METHOD];
    }
}
