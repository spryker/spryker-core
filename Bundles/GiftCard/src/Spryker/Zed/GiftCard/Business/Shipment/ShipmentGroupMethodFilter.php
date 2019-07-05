<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\Shipment;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Spryker\Zed\GiftCard\GiftCardConfig;

class ShipmentGroupMethodFilter implements ShipmentGroupMethodFilterInterface
{
    /**
     * @deprecated Use GiftCardConfig::getGiftCardOnlyShipmentMethods() instead.
     */
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
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethods
     */
    public function filterShipmentMethods(ShipmentGroupTransfer $shipmentGroupTransfer): ArrayObject
    {
        if ($this->containsOnlyGiftCardItems($shipmentGroupTransfer)) {
            return $this->filterGiftCardOnlyShipmentMethods($shipmentGroupTransfer->getAvailableShipmentMethods());
        }

        return $this->filterGiftCardShipmentMethods($shipmentGroupTransfer->getAvailableShipmentMethods());
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

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[]
     */
    protected function filterGiftCardOnlyShipmentMethods(ShipmentMethodsTransfer $shipmentMethodsTransfer): ArrayObject
    {
        $shipmentMethodsTransferForRemoveIndexes = [];
        $giftCardOnlyShipmentMethods = $this->getGiftCardOnlyShipmentMethods();
        $shipmentMethodsTransferList = $shipmentMethodsTransfer->getMethods();
        foreach ($shipmentMethodsTransferList as $shipmentMethodIndex => $shipmentMethodTransfer) {
            if (!in_array($shipmentMethodTransfer->getName(), $giftCardOnlyShipmentMethods)) {
                $shipmentMethodsTransferForRemoveIndexes[] = $shipmentMethodIndex;
            }
        }

        return $this->removeShipmentMethodTransferByIndexes(
            $shipmentMethodsTransferList,
            $shipmentMethodsTransferForRemoveIndexes
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[]
     */
    protected function filterGiftCardShipmentMethods(ShipmentMethodsTransfer $shipmentMethodsTransfer): ArrayObject
    {
        $shipmentMethodsTransferForRemoveIndexes = [];
        $giftCardOnlyShipmentMethods = $this->getGiftCardOnlyShipmentMethods();
        $shipmentMethodsTransferList = $shipmentMethodsTransfer->getMethods();
        foreach ($shipmentMethodsTransferList as $shipmentMethodIndex => $shipmentMethodTransfer) {
            if (in_array($shipmentMethodTransfer->getName(), $giftCardOnlyShipmentMethods)) {
                $shipmentMethodsTransferForRemoveIndexes[] = $shipmentMethodIndex;
            }
        }

        return $this->removeShipmentMethodTransferByIndexes(
            $shipmentMethodsTransferList,
            $shipmentMethodsTransferForRemoveIndexes
        );
    }

    /**
     * @deprecated Added for BC reasons, will be removed in next major release. Use GiftCardConfig::getGiftCardOnlyShipmentMethods() instead.
     *
     * @return string[]
     */
    protected function getGiftCardOnlyShipmentMethods(): array
    {
        $giftCardOnlyShipmentMethods = $this->giftCardConfig->getGiftCardOnlyShipmentMethods();
        if (count($giftCardOnlyShipmentMethods) === 0) {
            return [static::NO_SHIPMENT_METHOD];
        }

        return $giftCardOnlyShipmentMethods;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethodsTransferList
     * @param int[] $shipmentMethodsTransferForRemoveIndexes
     *
     * @return \ArrayObject
     */
    protected function removeShipmentMethodTransferByIndexes(
        ArrayObject $shipmentMethodsTransferList,
        array $shipmentMethodsTransferForRemoveIndexes
    ): ArrayObject {
        foreach ($shipmentMethodsTransferForRemoveIndexes as $shipmentMethodsTransferForRemoveIndex) {
            $shipmentMethodsTransferList->offsetUnset($shipmentMethodsTransferForRemoveIndex);
        }

        return $shipmentMethodsTransferList;
    }
}
