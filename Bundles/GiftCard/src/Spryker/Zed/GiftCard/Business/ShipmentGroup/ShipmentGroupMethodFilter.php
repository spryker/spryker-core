<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\ShipmentGroup;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodGiftCardFilterInterface;

class ShipmentGroupMethodFilter implements ShipmentGroupMethodFilterInterface
{
    /**
     * @var \Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodGiftCardFilterInterface
     */
    protected $shipmentMethodGiftCardFilter;

    /**
     * @param \Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodGiftCardFilterInterface $shipmentMethodGiftCardFilter
     */
    public function __construct(ShipmentMethodGiftCardFilterInterface $shipmentMethodGiftCardFilter)
    {
        $this->shipmentMethodGiftCardFilter = $shipmentMethodGiftCardFilter;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethods
     */
    public function filterShipmentMethods(ShipmentGroupTransfer $shipmentGroupTransfer): ArrayObject
    {
        if ($this->containsOnlyGiftCardItems($shipmentGroupTransfer)) {
            return $this->shipmentMethodGiftCardFilter
                ->filterGiftCardShipmentMethods($shipmentGroupTransfer->getAvailableShipmentMethods(), false);
        }

        return $this->shipmentMethodGiftCardFilter
            ->filterGiftCardShipmentMethods($shipmentGroupTransfer->getAvailableShipmentMethods(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return bool
     */
    protected function containsOnlyGiftCardItems(ShipmentGroupTransfer $shipmentGroupTransfer): bool
    {
        foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
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
    protected function isGiftCard(ItemTransfer $itemTransfer): bool
    {
        $giftCardMetadataTransfer = $itemTransfer->getGiftCardMetadata();
        if ($giftCardMetadataTransfer === null) {
            return false;
        }

        return (bool)$giftCardMetadataTransfer->getIsGiftCard();
    }
}
