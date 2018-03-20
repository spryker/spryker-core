<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\Shipment;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class ShipmentMethodFilter implements ShipmentMethodFilterInterface
{
    const NO_SHIPMENT_METHOD = 'No shipment';

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethods
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethods
     */
    public function filterShipmentMethods(ArrayObject $shipmentMethods, QuoteTransfer $quoteTransfer)
    {
        if ($this->containsOnlyGiftCardItems($quoteTransfer)) {
            return $this->allowOnlyNoShipment($shipmentMethods);
        }

        return $this->disallowNoShipment($shipmentMethods);
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
    protected function allowOnlyNoShipment(ArrayObject $shipmentMethods)
    {
        $result = new ArrayObject();
        foreach ($shipmentMethods as $shipmentMethod) {
            if ($shipmentMethod->getName() === static::NO_SHIPMENT_METHOD) {
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
    protected function disallowNoShipment(ArrayObject $shipmentMethods)
    {
        $result = new ArrayObject();
        foreach ($shipmentMethods as $shipmentMethod) {
            if ($shipmentMethod->getName() !== static::NO_SHIPMENT_METHOD) {
                $result[] = $shipmentMethod;
            }
        }

        return $result;
    }
}
