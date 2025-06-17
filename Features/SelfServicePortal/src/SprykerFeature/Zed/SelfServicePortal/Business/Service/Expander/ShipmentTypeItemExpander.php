<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCriteriaTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Zed\ProductOfferShipmentType\Business\ProductOfferShipmentTypeFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader\ShipmentTypeReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class ShipmentTypeItemExpander implements ShipmentTypeItemExpanderInterface
{
    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader\ShipmentTypeReaderInterface $shipmentTypeReader
     * @param \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface $SelfServicePortalRepository
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\ProductOfferShipmentTypeFacadeInterface $productOfferShipmentTypeFacade
     */
    public function __construct(
        protected ShipmentTypeReaderInterface $shipmentTypeReader,
        protected SelfServicePortalRepositoryInterface $SelfServicePortalRepository,
        protected ProductOfferShipmentTypeFacadeInterface $productOfferShipmentTypeFacade
    ) {
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

        if (!$itemsWithoutShipmentType) {
            return;
        }

        $defaultShipmentType = $this->shipmentTypeReader->getDefaultShipmentType($storeName);

        if (!$defaultShipmentType) {
            return;
        }

        $this->expandProductsWithDefaultShipmentType($itemsWithoutShipmentType, $defaultShipmentType);
        $this->expandProductOffersWithDefaultShipmentType($itemsWithoutShipmentType, $defaultShipmentType);
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

        $shipmentTypeTransfersByUuid = $this->getShipmentTypeTransfersByUuid($shipmentTypeUuids, $storeName);
        if (!$shipmentTypeTransfersByUuid) {
            return;
        }

        foreach ($itemsWithShipmentType as $itemTransfer) {
            $this->assignShipmentTypeToItem($itemTransfer, $shipmentTypeTransfersByUuid);
        }
    }

    /**
     * @param array<string> $shipmentTypeUuids
     * @param string $storeName
     *
     * @return array<string, \Generated\Shared\Transfer\ShipmentTypeTransfer>
     */
    protected function getShipmentTypeTransfersByUuid(array $shipmentTypeUuids, string $storeName): array
    {
        return $this->shipmentTypeReader->getShipmentTypesIndexedByUuids(
            array_unique($shipmentTypeUuids),
            $storeName,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array<string, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfersByUuid
     *
     * @return void
     */
    protected function assignShipmentTypeToItem(
        ItemTransfer $itemTransfer,
        array $shipmentTypeTransfersByUuid
    ): void {
        $shipmentTypeUuid = $itemTransfer->getShipmentTypeOrFail()->getUuidOrFail();
        if (!isset($shipmentTypeTransfersByUuid[$shipmentTypeUuid])) {
            return;
        }

        $shipmentTypeTransfer = $shipmentTypeTransfersByUuid[$shipmentTypeUuid];
        $itemTransfer->setShipmentType($shipmentTypeTransfer);
        $this->setShipmentTypeUuid($itemTransfer, $shipmentTypeTransfer);
    }

    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemsWithoutShipmentType
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $defaultShipmentTypeTransfer
     *
     * @return void
     */
    protected function expandProductsWithDefaultShipmentType(array $itemsWithoutShipmentType, ShipmentTypeTransfer $defaultShipmentTypeTransfer): void
    {
        $productConcreteIds = [];
        foreach ($itemsWithoutShipmentType as $itemWithoutShipmentType) {
            if ($itemWithoutShipmentType->getProductOfferReference()) {
                continue;
            }

            if (!$itemWithoutShipmentType->getId()) {
                continue;
            }

            $productConcreteIds[] = $itemWithoutShipmentType->getId();
        }

        $productConcreteIdsWithDefaultShipmentType = $this->SelfServicePortalRepository->getProductIdsWithShipmentType($productConcreteIds, $defaultShipmentTypeTransfer->getNameOrFail());

        foreach ($itemsWithoutShipmentType as $itemWithoutShipmentType) {
            if (!in_array($itemWithoutShipmentType->getId(), $productConcreteIdsWithDefaultShipmentType)) {
                continue;
            }

            $itemWithoutShipmentType->setShipmentType($defaultShipmentTypeTransfer);
            $this->setShipmentTypeUuid($itemWithoutShipmentType, $defaultShipmentTypeTransfer);
        }
    }

    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemsWithoutShipmentType
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $defaultShipmentTypeTransfer
     *
     * @return void
     */
    protected function expandProductOffersWithDefaultShipmentType(array $itemsWithoutShipmentType, ShipmentTypeTransfer $defaultShipmentTypeTransfer): void
    {
        $productOfferReferences = $this->extractProductOfferReferences($itemsWithoutShipmentType);
        if (!$productOfferReferences) {
            return;
        }

        $productOfferShipmentTypeCollection = $this->getProductOfferShipmentTypeCollection(
            $productOfferReferences,
            $defaultShipmentTypeTransfer,
        );

        $productOfferReferencesWithDefaultShipmentType = $this->extractProductOfferReferencesWithDefaultShipmentType(
            $productOfferShipmentTypeCollection,
        );

        $this->assignDefaultShipmentTypeToProductOfferItems(
            $itemsWithoutShipmentType,
            $productOfferReferencesWithDefaultShipmentType,
            $defaultShipmentTypeTransfer,
        );
    }

    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemsWithoutShipmentType
     *
     * @return list<string>
     */
    protected function extractProductOfferReferences(array $itemsWithoutShipmentType): array
    {
        $productOfferReferences = [];

        foreach ($itemsWithoutShipmentType as $itemWithoutShipmentType) {
            if (!$itemWithoutShipmentType->getProductOfferReference()) {
                continue;
            }

            $productOfferReferences[] = $itemWithoutShipmentType->getProductOfferReferenceOrFail();
        }

        return $productOfferReferences;
    }

    /**
     * @param list<string> $productOfferReferences
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $defaultShipmentTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer
     */
    protected function getProductOfferShipmentTypeCollection(
        array $productOfferReferences,
        ShipmentTypeTransfer $defaultShipmentTypeTransfer
    ): ProductOfferShipmentTypeCollectionTransfer {
        return $this->productOfferShipmentTypeFacade->getProductOfferShipmentTypeCollection(
            (new ProductOfferShipmentTypeCriteriaTransfer())
                ->setProductOfferShipmentTypeConditions(
                    (new ProductOfferShipmentTypeConditionsTransfer())
                        ->setProductOfferReferences($productOfferReferences)
                        ->addShipmentTypeName($defaultShipmentTypeTransfer->getNameOrFail()),
                ),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollection
     *
     * @return list<string>
     */
    protected function extractProductOfferReferencesWithDefaultShipmentType(
        ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollection
    ): array {
        $productOfferReferencesWithDefaultShipmentType = [];

        foreach ($productOfferShipmentTypeCollection->getProductOfferShipmentTypes() as $productOfferShipmentTypeTransfer) {
            if ($productOfferShipmentTypeTransfer->getProductOffer() === null) {
                continue;
            }

            $productOfferReferencesWithDefaultShipmentType[] = $productOfferShipmentTypeTransfer->getProductOfferOrFail()
                ->getProductOfferReferenceOrFail();
        }

        return $productOfferReferencesWithDefaultShipmentType;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemsWithoutShipmentType
     * @param list<string> $productOfferReferencesWithDefaultShipmentType
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $defaultShipmentTypeTransfer
     *
     * @return void
     */
    protected function assignDefaultShipmentTypeToProductOfferItems(
        array $itemsWithoutShipmentType,
        array $productOfferReferencesWithDefaultShipmentType,
        ShipmentTypeTransfer $defaultShipmentTypeTransfer
    ): void {
        foreach ($itemsWithoutShipmentType as $itemWithoutShipmentType) {
            if (!in_array($itemWithoutShipmentType->getProductOfferReference(), $productOfferReferencesWithDefaultShipmentType)) {
                continue;
            }

            $itemWithoutShipmentType->setShipmentType($defaultShipmentTypeTransfer);
            $this->setShipmentTypeUuid($itemWithoutShipmentType, $defaultShipmentTypeTransfer);
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
