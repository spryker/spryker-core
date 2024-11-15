<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityCartConnector\Business\Filter;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\SellableItemResponseTransfer;
use Generated\Shared\Transfer\SellableItemsResponseTransfer;
use Spryker\Zed\AvailabilityCartConnector\Business\Creator\MessageCreatorInterface;
use Spryker\Zed\AvailabilityCartConnector\Business\Reader\SellableItemsReaderInterface;
use Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToMessengerFacadeInterface;

class CartChangeItemFilter implements CartChangeItemFilterInterface
{
 /**
  * @var \Spryker\Zed\AvailabilityCartConnector\Business\Reader\SellableItemsReaderInterface
  */
    protected SellableItemsReaderInterface $sellableItemsReader;

    /**
     * @var \Spryker\Zed\AvailabilityCartConnector\Business\Creator\MessageCreatorInterface
     */
    protected MessageCreatorInterface $messageCreator;

    /**
     * @var \Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToMessengerFacadeInterface
     */
    protected AvailabilityCartConnectorToMessengerFacadeInterface $messengerFacade;

    /**
     * @param \Spryker\Zed\AvailabilityCartConnector\Business\Reader\SellableItemsReaderInterface $sellableItemsReader
     * @param \Spryker\Zed\AvailabilityCartConnector\Business\Creator\MessageCreatorInterface $messageCreator
     * @param \Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(
        SellableItemsReaderInterface $sellableItemsReader,
        MessageCreatorInterface $messageCreator,
        AvailabilityCartConnectorToMessengerFacadeInterface $messengerFacade
    ) {
        $this->sellableItemsReader = $sellableItemsReader;
        $this->messageCreator = $messageCreator;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function filterOutUnavailableItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        $sellableItemsResponseTransfer = $this->sellableItemsReader->getSellableItems($cartChangeTransfer);

        $unavailableItemsEntityIdentifiers = [];
        $messageTransfersGroupedBySku = [];
        foreach ($cartChangeTransfer->getItems() as $entityIdentifier => $itemTransfer) {
            if ($itemTransfer->getAmount() !== null) {
                continue;
            }

            $sellableItemResponseTransfer = $this->findSellableItemResponseTransfer($sellableItemsResponseTransfer, $entityIdentifier);
            if ($sellableItemResponseTransfer === null) {
                $unavailableItemsEntityIdentifiers[] = $entityIdentifier;

                continue;
            }

            if ($sellableItemResponseTransfer->getIsSellable()) {
                continue;
            }

            $messageTransfersGroupedBySku = $this->addMessage($sellableItemResponseTransfer, $messageTransfersGroupedBySku);

            $availableQuantity = $sellableItemResponseTransfer->getAvailableQuantityOrFail();
            if ($availableQuantity->equals(0) || !$availableQuantity->lessThan($itemTransfer->getQuantityOrFail())) {
                $unavailableItemsEntityIdentifiers[] = $entityIdentifier;

                continue;
            }

            $itemTransfer->setQuantity($availableQuantity->toInt());
        }

        return $this->removeUnavailableItems($cartChangeTransfer, $unavailableItemsEntityIdentifiers);
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemsResponseTransfer $sellableItemsResponseTransfer
     * @param string $entityIdentifier
     *
     * @return \Generated\Shared\Transfer\SellableItemResponseTransfer|null
     */
    protected function findSellableItemResponseTransfer(
        SellableItemsResponseTransfer $sellableItemsResponseTransfer,
        string $entityIdentifier
    ): ?SellableItemResponseTransfer {
        foreach ($sellableItemsResponseTransfer->getSellableItemResponses() as $sellableItemResponseTransfer) {
            if ($sellableItemResponseTransfer->getProductAvailabilityCriteriaOrFail()->getEntityIdentifierOrFail() === $entityIdentifier) {
                return $sellableItemResponseTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param list<string> $unavailableItemsEntityIdentifiers
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function removeUnavailableItems(
        CartChangeTransfer $cartChangeTransfer,
        array $unavailableItemsEntityIdentifiers
    ): CartChangeTransfer {
        foreach ($unavailableItemsEntityIdentifiers as $entityIdentifier) {
            $cartChangeTransfer->getItems()->offsetUnset($entityIdentifier);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemResponseTransfer $sellableItemResponseTransfer
     * @param array<string, array<string, \Generated\Shared\Transfer\MessageTransfer>> $messageTransfersGroupedBySku
     *
     * @return array<string, array<string, \Generated\Shared\Transfer\MessageTransfer>>
     */
    protected function addMessage(
        SellableItemResponseTransfer $sellableItemResponseTransfer,
        array $messageTransfersGroupedBySku
    ): array {
        $sku = $sellableItemResponseTransfer->getSkuOrFail();
        $messageTransfer = $this->messageCreator->createItemIsNotAvailableMessage(
            $sellableItemResponseTransfer->getAvailableQuantityOrFail(),
            $sku,
        );

        if (isset($messageTransfersGroupedBySku[$sku][$messageTransfer->getValueOrFail()])) {
            return $messageTransfersGroupedBySku;
        }

        $messageTransfersGroupedBySku[$sku][$messageTransfer->getValueOrFail()] = $messageTransfer;
        $this->messengerFacade->addInfoMessage($messageTransfersGroupedBySku[$sku][$messageTransfer->getValueOrFail()]);

        return $messageTransfersGroupedBySku;
    }
}
