<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use SprykerFeature\Zed\SspServiceManagement\Business\Reader\ShipmentTypeReaderInterface;

class ShipmentTypeItemExpander implements ShipmentTypeItemExpanderInterface
{
    /**
     * @param \SprykerFeature\Zed\SspServiceManagement\Business\Reader\ShipmentTypeReaderInterface $shipmentTypeReader
     */
    public function __construct(protected ShipmentTypeReaderInterface $shipmentTypeReader)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartItemsWithShipmentType(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        if (!$cartChangeTransfer->getItems()->count()) {
            return $cartChangeTransfer;
        }

        $storeName = $cartChangeTransfer->getQuoteOrFail()->getStoreOrFail()->getNameOrFail();
        $this->expandItemsWithShipmentType($cartChangeTransfer->getItems(), $storeName);
        $this->expandItemsWithShipmentType($cartChangeTransfer->getQuoteOrFail()->getBundleItems(), $storeName);

        if ($cartChangeTransfer->getQuoteOrFail()->getBundleItems()->count()) {
            $this->expandBundleRelatedItems(
                $cartChangeTransfer->getItems(),
                $cartChangeTransfer->getQuoteOrFail()->getBundleItems(),
            );
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteItemsWithShipmentType(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if (!$quoteTransfer->getItems()->count()) {
            return $quoteTransfer;
        }

        $storeName = $quoteTransfer->getStoreOrFail()->getNameOrFail();
        $this->expandItemsWithShipmentType($quoteTransfer->getItems(), $storeName);
        $this->expandItemsWithShipmentType($quoteTransfer->getBundleItems(), $storeName);

        if ($quoteTransfer->getBundleItems()->count()) {
            $this->expandBundleRelatedItems(
                $quoteTransfer->getItems(),
                $quoteTransfer->getBundleItems(),
            );
        }

        return $quoteTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param string $storeName
     *
     * @return void
     */
    protected function expandItemsWithShipmentType(ArrayObject $itemTransfers, string $storeName): void
    {
        [$itemsWithShipmentType, $itemsWithoutShipmentType, $shipmentTypeUuids] = $this->groupItemsByShipmentType($itemTransfers);

        $this->expandExistingShipmentTypes($itemsWithShipmentType, $shipmentTypeUuids, $storeName);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array{
     *     0: list<\Generated\Shared\Transfer\ItemTransfer>,
     *     1: list<\Generated\Shared\Transfer\ItemTransfer>,
     *     2: list<string>
     * }
     */
    protected function groupItemsByShipmentType(ArrayObject $itemTransfers): array
    {
        $itemsWithShipmentType = [];
        $itemsWithoutShipmentType = [];
        $shipmentTypeUuids = [];

        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getShipmentType() !== null && $itemTransfer->getShipmentType()->getUuid() !== null) {
                $itemsWithShipmentType[] = $itemTransfer;
                $shipmentTypeUuids[] = $itemTransfer->getShipmentTypeOrFail()->getUuidOrFail();

                continue;
            }

            $itemsWithoutShipmentType[] = $itemTransfer;
        }

        return [$itemsWithShipmentType, $itemsWithoutShipmentType, $shipmentTypeUuids];
    }

    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemsWithShipmentType
     * @param array<string> $shipmentTypeUuids
     * @param string $storeName
     *
     * @return void
     */
    protected function expandExistingShipmentTypes(array $itemsWithShipmentType, array $shipmentTypeUuids, string $storeName): void
    {
        if (!$itemsWithShipmentType) {
            return;
        }

        $shipmentTypeTransfersByUuid = $this->shipmentTypeReader->getShipmentTypesIndexedByUuids(
            array_unique($shipmentTypeUuids),
            $storeName,
        );

        foreach ($itemsWithShipmentType as $itemTransfer) {
            $shipmentTypeUuid = $itemTransfer->getShipmentTypeOrFail()->getUuidOrFail();
            if (!isset($shipmentTypeTransfersByUuid[$shipmentTypeUuid])) {
                continue;
            }

            $itemTransfer->setShipmentType($shipmentTypeTransfersByUuid[$shipmentTypeUuid]);
            $this->setShipmentTypeUuid($itemTransfer, $shipmentTypeTransfersByUuid[$shipmentTypeUuid]);
        }
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $bundleItemTransfers
     *
     * @return void
     */
    protected function expandBundleRelatedItems(
        ArrayObject $itemTransfers,
        ArrayObject $bundleItemTransfers
    ): void {
        $bundleItemTransfersByIdentifier = $this->indexBundleItemsByIdentifier($bundleItemTransfers);

        foreach ($itemTransfers as $itemTransfer) {
            if (!$itemTransfer->getRelatedBundleItemIdentifier()) {
                continue;
            }

            $bundleItemIdentifier = $itemTransfer->getRelatedBundleItemIdentifier();
            if (!isset($bundleItemTransfersByIdentifier[$bundleItemIdentifier])) {
                continue;
            }

            $bundleItemTransfer = $bundleItemTransfersByIdentifier[$bundleItemIdentifier];
            if (!$bundleItemTransfer->getShipmentType()) {
                continue;
            }

            $itemTransfer->setShipmentType($bundleItemTransfer->getShipmentType());
            $this->setShipmentTypeUuid($itemTransfer, $bundleItemTransfer->getShipmentTypeOrFail());
        }
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $bundleItemTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function indexBundleItemsByIdentifier(ArrayObject $bundleItemTransfers): array
    {
        $indexedBundleItems = [];

        foreach ($bundleItemTransfers as $bundleItemTransfer) {
            if ($bundleItemTransfer->getBundleItemIdentifier()) {
                $indexedBundleItems[$bundleItemTransfer->getBundleItemIdentifier()] = $bundleItemTransfer;
            }
        }

        return $indexedBundleItems;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function setShipmentTypeUuid(
        ItemTransfer $itemTransfer,
        ShipmentTypeTransfer $shipmentTypeTransfer
    ): ItemTransfer {
        if ($itemTransfer->getShipment() === null) {
            return $itemTransfer;
        }

        $itemTransfer->getShipmentOrFail()->setShipmentTypeUuid($shipmentTypeTransfer->getUuidOrFail());

        return $itemTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function filterOutBundleItems(ArrayObject $itemTransfers): ArrayObject
    {
        $bundleItems = new ArrayObject();

        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getRelatedBundleItemIdentifier()) {
                $bundleItems->append($itemTransfer);
            }
        }

        return $bundleItems;
    }
}
