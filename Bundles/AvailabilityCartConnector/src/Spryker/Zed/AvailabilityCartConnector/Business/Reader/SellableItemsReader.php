<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityCartConnector\Business\Reader;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\SellableItemRequestTransfer;
use Generated\Shared\Transfer\SellableItemsRequestTransfer;
use Generated\Shared\Transfer\SellableItemsResponseTransfer;
use Spryker\Zed\AvailabilityCartConnector\Business\Calculator\ItemQuantityCalculatorInterface;
use Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToAvailabilityInterface;

class SellableItemsReader implements SellableItemsReaderInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityCartConnector\Business\Calculator\ItemQuantityCalculatorInterface
     */
    protected ItemQuantityCalculatorInterface $itemQuantityCalculator;

    /**
     * @var \Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToAvailabilityInterface
     */
    protected AvailabilityCartConnectorToAvailabilityInterface $availabilityFacade;

    /**
     * @param \Spryker\Zed\AvailabilityCartConnector\Business\Calculator\ItemQuantityCalculatorInterface $itemQuantityCalculator
     * @param \Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToAvailabilityInterface $availabilityFacade
     */
    public function __construct(
        ItemQuantityCalculatorInterface $itemQuantityCalculator,
        AvailabilityCartConnectorToAvailabilityInterface $availabilityFacade
    ) {
        $this->itemQuantityCalculator = $itemQuantityCalculator;
        $this->availabilityFacade = $availabilityFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemsResponseTransfer
     */
    public function getSellableItems(CartChangeTransfer $cartChangeTransfer): SellableItemsResponseTransfer
    {
        $sellableItemsRequestTransfer = $this->createSellableItemsRequestTransfer($cartChangeTransfer);

        return $this->availabilityFacade->areProductsSellableForStore($sellableItemsRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemsRequestTransfer
     */
    protected function createSellableItemsRequestTransfer(CartChangeTransfer $cartChangeTransfer): SellableItemsRequestTransfer
    {
        $cartChangeTransfer->getQuote()->requireStore();
        $storeTransfer = $cartChangeTransfer->getQuoteOrFail()->getStoreOrFail();

        $itemsInCart = clone $cartChangeTransfer->getQuote()->getItems();
        $sellableItemsRequestTransfer = (new SellableItemsRequestTransfer())->setStore($storeTransfer);
        foreach ($cartChangeTransfer->getItems() as $entityIdentifier => $itemTransfer) {
            if ($itemTransfer->getAmount() !== null) {
                continue;
            }

            $sellableItemRequestTransfer = new SellableItemRequestTransfer();
            $sellableItemRequestTransfer->setQuantity(
                $this->itemQuantityCalculator->calculateTotalItemQuantity($itemsInCart, $itemTransfer),
            );
            $sellableItemRequestTransfer->setProductAvailabilityCriteria(
                (new ProductAvailabilityCriteriaTransfer())
                    ->setEntityIdentifier($entityIdentifier)
                    ->fromArray($itemTransfer->toArray(), true),
            );
            $itemsInCart->append($itemTransfer);
            $sellableItemRequestTransfer->setSku($itemTransfer->getSku());
            $sellableItemsRequestTransfer->addSellableItemRequest($sellableItemRequestTransfer);
        }

        return $sellableItemsRequestTransfer;
    }
}
