<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypesRestApi\Business\Expander;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer;
use Spryker\Zed\ShipmentTypesRestApi\Dependency\Facade\ShipmentTypesRestApiToShipmentFacadeInterface;

class QuoteItemExpander implements QuoteItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\ShipmentTypesRestApi\Dependency\Facade\ShipmentTypesRestApiToShipmentFacadeInterface
     */
    protected ShipmentTypesRestApiToShipmentFacadeInterface $shipmentFacade;

    /**
     * @param \Spryker\Zed\ShipmentTypesRestApi\Dependency\Facade\ShipmentTypesRestApiToShipmentFacadeInterface $shipmentFacade
     */
    public function __construct(ShipmentTypesRestApiToShipmentFacadeInterface $shipmentFacade)
    {
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if (!$this->hasAtLeastOneShipmentMethod($quoteTransfer)) {
            return $quoteTransfer;
        }

        $shipmentMethodsCollectionTransfer = $this->shipmentFacade->getAvailableMethodsByShipment($quoteTransfer);
        $shipmentTypeTransfersIndexedByIdShipmentMethod = $this->getShipmentTypeTransfersIndexedByIdShipmentMethod(
            $shipmentMethodsCollectionTransfer,
        );
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$this->hasShipmentMethod($itemTransfer)) {
                continue;
            }
            $idShipmentMethod = $itemTransfer->getShipmentOrFail()->getMethodOrFail()->getIdShipmentMethodOrFail();
            $shipmentTypeTransfer = $shipmentTypeTransfersIndexedByIdShipmentMethod[$idShipmentMethod] ?? null;

            $itemTransfer->setShipmentType($shipmentTypeTransfer);

            if ($shipmentTypeTransfer) {
                $itemTransfer->getShipmentOrFail()->setShipmentTypeUuid($shipmentTypeTransfer->getUuidOrFail());
            }
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer $shipmentMethodsCollectionTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\ShipmentTypeTransfer>
     */
    protected function getShipmentTypeTransfersIndexedByIdShipmentMethod(
        ShipmentMethodsCollectionTransfer $shipmentMethodsCollectionTransfer
    ): array {
        $shipmentTypeTransfersIndexedByIdShipmentMethod = [];
        foreach ($shipmentMethodsCollectionTransfer->getShipmentMethods() as $shipmentMethodsTransfer) {
            foreach ($shipmentMethodsTransfer->getMethods() as $shipmentMethodTransfer) {
                $idShipmentMethod = $shipmentMethodTransfer->getIdShipmentMethodOrFail();
                $shipmentTypeTransfersIndexedByIdShipmentMethod[$idShipmentMethod] = $shipmentMethodTransfer->getShipmentType();
            }
        }

        return array_filter($shipmentTypeTransfersIndexedByIdShipmentMethod);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function hasAtLeastOneShipmentMethod(QuoteTransfer $quoteTransfer): bool
    {
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($this->hasShipmentMethod($itemTransfer)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function hasShipmentMethod(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getShipment() && $itemTransfer->getShipmentOrFail()->getMethod();
    }
}
