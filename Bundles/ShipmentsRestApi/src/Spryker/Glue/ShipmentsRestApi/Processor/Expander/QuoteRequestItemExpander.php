<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Expander;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestQuoteRequestsAddressTransfer;
use Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer;
use Generated\Shared\Transfer\RestQuoteRequestShipmentTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Glue\ShipmentsRestApi\Dependency\Service\ShipmentsRestApiToShipmentServiceInterface;

class QuoteRequestItemExpander implements QuoteRequestItemExpanderInterface
{
    /**
     * @var \Spryker\Glue\ShipmentsRestApi\Dependency\Service\ShipmentsRestApiToShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @param \Spryker\Glue\ShipmentsRestApi\Dependency\Service\ShipmentsRestApiToShipmentServiceInterface $shipmentService
     */
    public function __construct(ShipmentsRestApiToShipmentServiceInterface $shipmentService)
    {
        $this->shipmentService = $shipmentService;
    }

    /**
     * @param array<\Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer> $restQuoteRequestsAttributesTransfers
     * @param array<\Generated\Shared\Transfer\QuoteRequestTransfer> $quoteRequestTransfers
     * @param string $localeName
     *
     * @return array<\Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer>
     */
    public function expandRestQuoteRequestItemWithShipments(
        array $restQuoteRequestsAttributesTransfers,
        array $quoteRequestTransfers,
        string $localeName
    ): array {
        $indexedRestQuoteRequestsAttributesTransfers = $this->getRestQuoteRequestsAttributesTransfersIndexedByQuoteRequestReference($restQuoteRequestsAttributesTransfers);
        foreach ($quoteRequestTransfers as $quoteRequestTransfer) {
            if (!isset($indexedRestQuoteRequestsAttributesTransfers[$quoteRequestTransfer->getQuoteRequestReference()])) {
                continue;
            }

            $restQuoteRequestsAttributesTransfer = $indexedRestQuoteRequestsAttributesTransfers[$quoteRequestTransfer->getQuoteRequestReference()];
            if (!$this->isQuoteRequestValid($quoteRequestTransfer, $restQuoteRequestsAttributesTransfer)) {
                continue;
            }

            $quoteTransfer = $quoteRequestTransfer->getLatestVersion()->getQuote();

            if ($this->shipmentExistsForItems($quoteTransfer)) {
                $shipmentGroupTransfers = $this->shipmentService->groupItemsByShipment($quoteTransfer->getItems());

                $restQuoteRequestsCartTransfer = $restQuoteRequestsAttributesTransfer->getShownVersion()->getCart();
                $restQuoteRequestItemTransfers = ($restQuoteRequestsCartTransfer->getItems())->getArrayCopy();

                foreach ($shipmentGroupTransfers as $shipmentGroupTransfer) {
                    if (
                        $shipmentGroupTransfer->getShipment() !== null
                        && $shipmentGroupTransfer->getShipment()->getMethod() !== null
                    ) {
                        $restQuoteRequestShipmentTransfer = $this->createRestQuoteRequestShipmentTransfer(
                            $restQuoteRequestItemTransfers,
                            $shipmentGroupTransfer,
                        );
                        $restQuoteRequestsCartTransfer->addShipment($restQuoteRequestShipmentTransfer);
                    }
                }
            }
        }

        return $restQuoteRequestsAttributesTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\RestQuoteRequestItemTransfer> $restQuoteRequestItemTransfers
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestShipmentTransfer|null
     */
    protected function createRestQuoteRequestShipmentTransfer(
        array $restQuoteRequestItemTransfers,
        ShipmentGroupTransfer $shipmentGroupTransfer
    ): ?RestQuoteRequestShipmentTransfer {
        $restQuoteRequestShipmentTransfer = new RestQuoteRequestShipmentTransfer();
        $shipmentTransfer = $shipmentGroupTransfer->getShipment();
        if ($shipmentTransfer === null) {
            return null;
        }

        $restQuoteRequestShipmentTransfer->setMethod($shipmentTransfer->getMethod()->getName());

        $restQuoteRequestShipmentTransfer = $this->createRestQuoteRequestsAddressTransfer(
            $restQuoteRequestShipmentTransfer,
            $shipmentTransfer->getShippingAddress(),
        );

        foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
            $foundRestQuoteRequestItemTransfer = null;
            foreach ($restQuoteRequestItemTransfers as $restQuoteRequestItemTransfer) {
                if ($itemTransfer->getGroupKey() === $restQuoteRequestItemTransfer->getGroupKey()) {
                    $foundRestQuoteRequestItemTransfer = $restQuoteRequestItemTransfer;

                    break;
                }
            }

            if ($foundRestQuoteRequestItemTransfer !== null) {
                $restQuoteRequestShipmentTransfer->addItem($foundRestQuoteRequestItemTransfer);
            }
        }

        return $restQuoteRequestShipmentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteRequestShipmentTransfer $restQuoteRequestShipmentTransfer
     * @param \Generated\Shared\Transfer\AddressTransfer $shipmentAddress
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestShipmentTransfer
     */
    protected function createRestQuoteRequestsAddressTransfer(
        RestQuoteRequestShipmentTransfer $restQuoteRequestShipmentTransfer,
        AddressTransfer $shipmentAddress
    ) {
        $restAddressTransfer = new RestQuoteRequestsAddressTransfer();
        $restAddressTransfer->fromArray($shipmentAddress->toArray(), true);
        $restQuoteRequestShipmentTransfer->setShippingAddress($restAddressTransfer);

        return $restQuoteRequestShipmentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function shipmentExistsForItems(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() === null) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer $restQuoteRequestsAttributesTransfer
     *
     * @return bool
     */
    protected function isQuoteRequestValid(
        QuoteRequestTransfer $quoteRequestTransfer,
        RestQuoteRequestsAttributesTransfer $restQuoteRequestsAttributesTransfer
    ): bool {
        return $quoteRequestTransfer->getLatestVersion() !== null
            && $quoteRequestTransfer->getLatestVersion()->getQuote() !== null
            && $restQuoteRequestsAttributesTransfer->getShownVersion() !== null
            && $restQuoteRequestsAttributesTransfer->getShownVersion()->getCart() !== null;
    }

    /**
     * @param array<\Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer> $restQuoteRequestsAttributesTransfers
     *
     * @return array<\Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer>
     */
    protected function getRestQuoteRequestsAttributesTransfersIndexedByQuoteRequestReference(array $restQuoteRequestsAttributesTransfers): array
    {
        $indexedRestQuoteRequestsAttributesTransfers = [];
        foreach ($restQuoteRequestsAttributesTransfers as $restQuoteRequestsAttributesTransfer) {
            $indexedRestQuoteRequestsAttributesTransfers[$restQuoteRequestsAttributesTransfer->getQuoteRequestReference()] = $restQuoteRequestsAttributesTransfer;
        }

        return $indexedRestQuoteRequestsAttributesTransfers;
    }
}
