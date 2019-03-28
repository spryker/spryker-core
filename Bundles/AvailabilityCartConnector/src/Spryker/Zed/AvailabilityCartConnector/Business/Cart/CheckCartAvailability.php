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
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToAvailabilityInterface;
use Spryker\Zed\AvailabilityCartConnector\Dependency\Service\AvailabilityCartConnectorToUtilQuantityServiceInterface;

class CheckCartAvailability implements CheckCartAvailabilityInterface
{
    public const CART_PRE_CHECK_AVAILABILITY_FAILED = 'cart.pre.check.availability.failed';
    public const CART_PRE_CHECK_AVAILABILITY_EMPTY = 'cart.pre.check.availability.failed.empty';
    public const STOCK_TRANSLATION_PARAMETER = '%stock%';
    public const SKU_TRANSLATION_PARAMETER = '%sku%';

    /**
     * @var \Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToAvailabilityInterface
     */
    protected $availabilityFacade;

    /**
     * @var \Spryker\Zed\AvailabilityCartConnector\Dependency\Service\AvailabilityCartConnectorToUtilQuantityServiceInterface
     */
    protected $utilQuantityService;

    /**
     * @param \Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToAvailabilityInterface $availabilityFacade
     * @param \Spryker\Zed\AvailabilityCartConnector\Dependency\Service\AvailabilityCartConnectorToUtilQuantityServiceInterface $utilQuantityService
     */
    public function __construct(
        AvailabilityCartConnectorToAvailabilityInterface $availabilityFacade,
        AvailabilityCartConnectorToUtilQuantityServiceInterface $utilQuantityService
    ) {
        $this->availabilityFacade = $availabilityFacade;
        $this->utilQuantityService = $utilQuantityService;
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

        $messages = new ArrayObject();
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $currentItemQuantity = $this->calculateCurrentCartQuantityForGivenSku(
                $itemsInCart,
                $itemTransfer->getSku()
            );
            $currentItemQuantity = $this->sumQuantities($currentItemQuantity, $itemTransfer->getQuantity());

            $isSellable = $this->isProductSellable($itemTransfer, $currentItemQuantity, $storeTransfer);

            if (!$isSellable) {
                $stock = $this->calculateStockForProduct($itemTransfer, $storeTransfer);
                $cartPreCheckResponseTransfer->setIsSuccess(false);
                $messages[] = $this->createItemIsNotAvailableMessageTransfer($stock, $itemTransfer->getSku());
            }
            $itemsInCart->append($itemTransfer);
        }

        $cartPreCheckResponseTransfer->setMessages($messages);

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return float
     */
    protected function sumQuantities(float $firstQuantity, float $secondQuantity): float
    {
        return $this->utilQuantityService->sumQuantities($firstQuantity, $secondQuantity);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     * @param string $sku
     *
     * @return int
     */
    protected function calculateCurrentCartQuantityForGivenSku(ArrayObject $items, $sku)
    {
        $quantity = 0;

        foreach ($items as $itemTransfer) {
            if ($itemTransfer->getSku() !== $sku) {
                continue;
            }

            $quantity = $this->sumQuantities($quantity, $itemTransfer->getQuantity());
        }

        return $quantity;
    }

    /**
     * @param int $stock
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createItemIsNotAvailableMessageTransfer($stock, $sku)
    {
        $translationKey = $this->getTranslationKey($stock);

        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue($translationKey);
        $messageTransfer->setParameters([
            static::STOCK_TRANSLATION_PARAMETER => $stock,
            static::SKU_TRANSLATION_PARAMETER => $sku,
        ]);

        return $messageTransfer;
    }

    /**
     * @param int $stock
     *
     * @return string
     */
    protected function getTranslationKey($stock)
    {
        $translationKey = static::CART_PRE_CHECK_AVAILABILITY_FAILED;
        if ($stock <= 0) {
            $translationKey = static::CART_PRE_CHECK_AVAILABILITY_EMPTY;
        }
        return $translationKey;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getStoreTransfer(CartChangeTransfer $cartChangeTransfer)
    {
        $storeTransfer = $cartChangeTransfer->getQuote()->getStore();
        if (!$storeTransfer) {
            return new StoreTransfer();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param float $currentItemQuantity
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return bool
     */
    protected function isProductSellable(
        ItemTransfer $itemTransfer,
        $currentItemQuantity,
        ?StoreTransfer $storeTransfer = null
    ) {
        if ($storeTransfer) {
            return $this->availabilityFacade->isProductSellableForStore(
                $itemTransfer->getSku(),
                $currentItemQuantity,
                $storeTransfer
            );
        }

        return $this->availabilityFacade->isProductSellable($itemTransfer->getSku(), $currentItemQuantity);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return int
     */
    protected function calculateStockForProduct(ItemTransfer $itemTransfer, ?StoreTransfer $storeTransfer = null)
    {
        if ($storeTransfer) {
            $this->availabilityFacade->calculateStockForProductWithStore($itemTransfer->getSku(), $storeTransfer);
        }
        return $this->availabilityFacade->calculateStockForProduct($itemTransfer->getSku());
    }
}
