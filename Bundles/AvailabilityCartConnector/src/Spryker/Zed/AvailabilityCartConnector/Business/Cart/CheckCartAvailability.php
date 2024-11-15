<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityCartConnector\Business\Cart;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\SellableItemsResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\AvailabilityCartConnector\Business\Calculator\ItemQuantityCalculatorInterface;
use Spryker\Zed\AvailabilityCartConnector\Business\Creator\MessageCreatorInterface;
use Spryker\Zed\AvailabilityCartConnector\Business\Reader\SellableItemsReaderInterface;
use Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToAvailabilityInterface;

class CheckCartAvailability implements CheckCartAvailabilityInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityCartConnector\Business\Calculator\ItemQuantityCalculatorInterface
     */
    protected ItemQuantityCalculatorInterface $itemQuantityCalculator;

    /**
     * @var \Spryker\Zed\AvailabilityCartConnector\Business\Reader\SellableItemsReaderInterface
     */
    protected SellableItemsReaderInterface $sellableItemsReader;

    /**
     * @var \Spryker\Zed\AvailabilityCartConnector\Business\Creator\MessageCreatorInterface
     */
    protected MessageCreatorInterface $messageCreator;

    /**
     * @var \Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToAvailabilityInterface
     */
    protected AvailabilityCartConnectorToAvailabilityInterface $availabilityFacade;

    /**
     * @param \Spryker\Zed\AvailabilityCartConnector\Business\Calculator\ItemQuantityCalculatorInterface $itemQuantityCalculator
     * @param \Spryker\Zed\AvailabilityCartConnector\Business\Reader\SellableItemsReaderInterface $sellableItemsReader
     * @param \Spryker\Zed\AvailabilityCartConnector\Business\Creator\MessageCreatorInterface $messageCreator
     * @param \Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToAvailabilityInterface $availabilityFacade
     */
    public function __construct(
        ItemQuantityCalculatorInterface $itemQuantityCalculator,
        SellableItemsReaderInterface $sellableItemsReader,
        MessageCreatorInterface $messageCreator,
        AvailabilityCartConnectorToAvailabilityInterface $availabilityFacade
    ) {
        $this->itemQuantityCalculator = $itemQuantityCalculator;
        $this->sellableItemsReader = $sellableItemsReader;
        $this->messageCreator = $messageCreator;
        $this->availabilityFacade = $availabilityFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkCartAvailability(CartChangeTransfer $cartChangeTransfer)
    {
        $cartPreCheckResponseTransfer = new CartPreCheckResponseTransfer();
        $cartPreCheckResponseTransfer->setIsSuccess(true);

        $storeTransfer = $this->getStoreTransfer($cartChangeTransfer);
        $itemsInCart = clone $cartChangeTransfer->getQuote()->getItems();

        /** @var \ArrayObject<int, \Generated\Shared\Transfer\MessageTransfer> $messages */
        $messages = new ArrayObject();
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getAmount() !== null) {
                continue;
            }

            $currentItemQuantity = $this->itemQuantityCalculator->calculateCartItemQuantity($itemsInCart, $itemTransfer);
            $currentItemQuantity += $itemTransfer->getQuantity();

            $productAvailabilityCriteriaTransfer = (new ProductAvailabilityCriteriaTransfer())
                ->fromArray($itemTransfer->toArray(), true);

            $isSellable = $this->availabilityFacade->isProductSellableForStore(
                $itemTransfer->getSku(),
                new Decimal($currentItemQuantity),
                $storeTransfer,
                $productAvailabilityCriteriaTransfer,
            );

            if (!$isSellable) {
                $availability = $this->findProductConcreteAvailability($itemTransfer, $storeTransfer, $productAvailabilityCriteriaTransfer);
                $cartPreCheckResponseTransfer->setIsSuccess(false);
                $messages[] = $this->messageCreator->createItemIsNotAvailableMessage($availability, $itemTransfer->getSku());
            }

            $itemsInCart->append($itemTransfer);
        }

        $cartPreCheckResponseTransfer->setMessages($messages);

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkCartAvailabilityBatch(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $sellableItemsResponseTransfer = $this->sellableItemsReader->getSellableItems($cartChangeTransfer);

        return $this->createCartPreCheckResponseTransfer($sellableItemsResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemsResponseTransfer $sellableItemsResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function createCartPreCheckResponseTransfer(
        SellableItemsResponseTransfer $sellableItemsResponseTransfer
    ): CartPreCheckResponseTransfer {
        $cartPreCheckResponseTransfer = new CartPreCheckResponseTransfer();
        $cartPreCheckResponseTransfer->setIsSuccess(true);

        /** @var \ArrayObject<int, \Generated\Shared\Transfer\MessageTransfer> $messages */
        $messages = new ArrayObject();
        foreach ($sellableItemsResponseTransfer->getSellableItemResponses() as $sellableItemResponseTransfer) {
            if (!$sellableItemResponseTransfer->getIsSellable()) {
                $cartPreCheckResponseTransfer->setIsSuccess(false);
                $messages[] = $this->messageCreator->createItemIsNotAvailableMessage(
                    $sellableItemResponseTransfer->getAvailableQuantity(),
                    $sellableItemResponseTransfer->getSku(),
                );
            }
        }
        $cartPreCheckResponseTransfer->setMessages($messages);

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getStoreTransfer(CartChangeTransfer $cartChangeTransfer): StoreTransfer
    {
        $cartChangeTransfer
            ->getQuote()
                ->requireStore();

        return $cartChangeTransfer->getQuote()->getStore();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer $productAvailabilityCriteriaTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function findProductConcreteAvailability(
        ItemTransfer $itemTransfer,
        StoreTransfer $storeTransfer,
        ProductAvailabilityCriteriaTransfer $productAvailabilityCriteriaTransfer
    ): Decimal {
        $productConcreteAvailabilityTransfer = $this->availabilityFacade
            ->findOrCreateProductConcreteAvailabilityBySkuForStore($itemTransfer->getSku(), $storeTransfer, $productAvailabilityCriteriaTransfer);

        if ($productConcreteAvailabilityTransfer !== null) {
            return $productConcreteAvailabilityTransfer->getAvailability();
        }

        return new Decimal(0);
    }
}
