<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentsRestApi\Business\Mapper;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestAddressTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestShipmentsTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\ShipmentsRestApi\Dependency\Facade\ShipmentsRestApiToShipmentFacadeInterface;

class ShipmentQuoteItemMapper implements ShipmentQuoteItemMapperInterface
{
    /**
     * @var \Spryker\Zed\ShipmentsRestApi\Dependency\Facade\ShipmentsRestApiToShipmentFacadeInterface
     */
    protected $shipmentFacade;

    /**
     * @var \Spryker\Zed\ShipmentsRestApiExtension\Dependency\Plugin\AddressProviderStrategyPluginInterface[]
     */
    protected $addressProviderStrategyPlugins;

    /**
     * @param \Spryker\Zed\ShipmentsRestApi\Dependency\Facade\ShipmentsRestApiToShipmentFacadeInterface $shipmentFacade
     * @param \Spryker\Zed\ShipmentsRestApiExtension\Dependency\Plugin\AddressProviderStrategyPluginInterface[] $addressProviderStrategyPlugins
     */
    public function __construct(
        ShipmentsRestApiToShipmentFacadeInterface $shipmentFacade,
        array $addressProviderStrategyPlugins
    ) {
        $this->shipmentFacade = $shipmentFacade;
        $this->addressProviderStrategyPlugins = $addressProviderStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapShipmentsToQuote(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        if (!$restCheckoutRequestAttributesTransfer->getShipments()->count()) {
            return $quoteTransfer;
        }

        foreach ($restCheckoutRequestAttributesTransfer->getShipments() as $restShipmentsTransfer) {
            $shipmentTransfer = (new ShipmentTransfer())
                ->fromArray($restShipmentsTransfer->toArray(), true);

            $shipmentTransfer = $this->expandShipmentTransferWithShippingAddress(
                $restShipmentsTransfer,
                $quoteTransfer,
                $shipmentTransfer
            );

            $shipmentTransfer = $this->expandShipmentTransferWithShipmentMethod(
                $restShipmentsTransfer,
                $quoteTransfer,
                $shipmentTransfer
            );

            $quoteTransfer = $this->assignShipmentTransferToItems(
                $quoteTransfer,
                $restShipmentsTransfer->getItems(),
                $shipmentTransfer
            );

            $quoteTransfer = $this->assignShipmentTransferToBundleItems(
                $quoteTransfer,
                $restShipmentsTransfer->getItems(),
                $shipmentTransfer
            );
        }

        $quoteTransfer = $this->shipmentFacade->expandQuoteWithShipmentGroups($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string[] $itemsGroupKeys
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function assignShipmentTransferToItems(
        QuoteTransfer $quoteTransfer,
        array $itemsGroupKeys,
        ShipmentTransfer $shipmentTransfer
    ): QuoteTransfer {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!in_array($itemTransfer->getGroupKey(), $itemsGroupKeys, true)) {
                continue;
            }

            $this->updateItemShipment($itemTransfer, $shipmentTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string[] $itemsGroupKeys
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function assignShipmentTransferToBundleItems(
        QuoteTransfer $quoteTransfer,
        array $itemsGroupKeys,
        ShipmentTransfer $shipmentTransfer
    ): QuoteTransfer {
        $mappedBundledItems = $this->mapBundledItemsByBundleItemIdentifier($quoteTransfer);

        foreach ($quoteTransfer->getBundleItems() as $itemTransfer) {
            if (!in_array($itemTransfer->getGroupKey(), $itemsGroupKeys, true)) {
                continue;
            }

            $bundledItems = $mappedBundledItems[$itemTransfer->getBundleItemIdentifier()] ?? [];

            $this->updateBundledItemsShipment($bundledItems, $shipmentTransfer);
            $this->updateItemShipment($itemTransfer, $shipmentTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $bundledItems
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return void
     */
    protected function updateBundledItemsShipment(array $bundledItems, ShipmentTransfer $shipmentTransfer): void
    {
        foreach ($bundledItems as $bundledItem) {
            $this->updateItemShipment($bundledItem, $shipmentTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return void
     */
    protected function updateItemShipment(ItemTransfer $itemTransfer, ShipmentTransfer $shipmentTransfer): void
    {
        if (!$itemTransfer->getShipment()) {
            $itemTransfer->setShipment($shipmentTransfer);

            return;
        }

        $itemTransfer->getShipment()->fromArray($shipmentTransfer->modifiedToArray());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[][]
     */
    protected function mapBundledItemsByBundleItemIdentifier(QuoteTransfer $quoteTransfer): array
    {
        $mappedBundledItems = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getRelatedBundleItemIdentifier()) {
                $mappedBundledItems[$itemTransfer->getRelatedBundleItemIdentifier()][] = $itemTransfer;
            }
        }

        return $mappedBundledItems;
    }

    /**
     * @param \Generated\Shared\Transfer\RestShipmentsTransfer $restShipmentsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function expandShipmentTransferWithShipmentMethod(
        RestShipmentsTransfer $restShipmentsTransfer,
        QuoteTransfer $quoteTransfer,
        ShipmentTransfer $shipmentTransfer
    ): ShipmentTransfer {
        if (!$restShipmentsTransfer->getIdShipmentMethod()) {
            return $shipmentTransfer;
        }

        $shipmentMethodTransfer = $this->shipmentFacade
            ->findAvailableMethodById($restShipmentsTransfer->getIdShipmentMethod(), $quoteTransfer);

        if (!$shipmentMethodTransfer) {
            return $shipmentTransfer;
        }

        return $shipmentTransfer
            ->setMethod($shipmentMethodTransfer)
            ->setShipmentSelection((string)$shipmentMethodTransfer->getIdShipmentMethod());
    }

    /**
     * @param \Generated\Shared\Transfer\RestShipmentsTransfer $restShipmentsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function expandShipmentTransferWithShippingAddress(
        RestShipmentsTransfer $restShipmentsTransfer,
        QuoteTransfer $quoteTransfer,
        ShipmentTransfer $shipmentTransfer
    ): ShipmentTransfer {
        if (!$restShipmentsTransfer->getShippingAddress()) {
            return $shipmentTransfer;
        }

        $shipmentTransfer->setShippingAddress(
            $this->getAddressTransfer($restShipmentsTransfer->getShippingAddress(), $quoteTransfer)
        );

        return $shipmentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestAddressTransfer $restAddressTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function getAddressTransfer(
        RestAddressTransfer $restAddressTransfer,
        QuoteTransfer $quoteTransfer
    ): AddressTransfer {
        foreach ($this->addressProviderStrategyPlugins as $addressProviderStrategyPlugin) {
            if (!$addressProviderStrategyPlugin->isApplicable($restAddressTransfer)) {
                continue;
            }

            return $addressProviderStrategyPlugin->provideAddress($restAddressTransfer, $quoteTransfer);
        }

        return (new AddressTransfer())->fromArray($restAddressTransfer->toArray(), true);
    }
}
