<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\Shipment;

use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Shared\GiftCard\GiftCardConfig;
use Spryker\Zed\GiftCard\Business\Checker\GiftCardItemsCheckerInterface;

class ShipmentGroupsSanitizer implements ShipmentGroupsSanitizerInterface
{
    /**
     * @var \Spryker\Zed\GiftCard\Business\Checker\GiftCardItemsCheckerInterface
     */
    protected $giftCardItemsChecker;

    /**
     * @param \Spryker\Zed\GiftCard\Business\Checker\GiftCardItemsCheckerInterface $giftCardItemsChecker
     */
    public function __construct(GiftCardItemsCheckerInterface $giftCardItemsChecker)
    {
        $this->giftCardItemsChecker = $giftCardItemsChecker;
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection
     *
     * @return iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    public function sanitizeShipmentGroupCollection(iterable $shipmentGroupCollection): iterable
    {
        foreach ($shipmentGroupCollection as $shipmentGroupTransfer) {
            if ($this->giftCardItemsChecker->hasOnlyGiftCardItems($shipmentGroupTransfer->getItems()) === false) {
                continue;
            }

            $shipmentGroupTransfer = $this->sanitizeOnlyGiftCardItemsShipment($shipmentGroupTransfer);
        }

        return $shipmentGroupCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    protected function sanitizeOnlyGiftCardItemsShipment(ShipmentGroupTransfer $shipmentGroupTransfer): ShipmentGroupTransfer
    {
        $shipmentGroupTransfer->requireShipment()
            ->requireAvailableShipmentMethods();

        $noShipmentMethodTransfer = $this->findNoShipmentMethod($shipmentGroupTransfer->getAvailableShipmentMethods());
        $shipmentTransfer = $shipmentGroupTransfer->getShipment()
            ->setMethod($noShipmentMethodTransfer)
            ->setShipmentSelection(GiftCardConfig::SHIPMENT_METHOD_NAME_NO_SHIPMENT);

        foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setShipment($shipmentTransfer);
        }

        return $shipmentGroupTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    protected function findNoShipmentMethod(ShipmentMethodsTransfer $shipmentMethodsTransfer): ?ShipmentMethodTransfer
    {
        foreach ($shipmentMethodsTransfer->getMethods() as $shipmentMethodTransfer) {
            if ($shipmentMethodTransfer->getName() === GiftCardConfig::SHIPMENT_METHOD_NAME_NO_SHIPMENT) {
                return $shipmentMethodTransfer;
            }
        }

        return null;
    }
}
