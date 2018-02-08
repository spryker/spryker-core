<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityCartConnector\Business\Cart;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToAvailabilityInterface;

class CheckCartAvailability implements CheckCartAvailabilityInterface
{
    const CART_PRE_CHECK_AVAILABILITY_FAILED = 'cart.pre.check.availability.failed';
    const CART_PRE_CHECK_AVAILABILITY_EMPTY = 'cart.pre.check.availability.failed.empty';
    const STOCK_TRANSLATION_PARAMETER = 'stock';

    /**
     * @var \Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToAvailabilityInterface
     */
    protected $availabilityFacade;

    /**
     * @param \Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToAvailabilityInterface $availabilityFacade
     */
    public function __construct(AvailabilityCartConnectorToAvailabilityInterface $availabilityFacade)
    {
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

        $storeTransfer = $cartChangeTransfer->getQuote()->getStore();

        $messages = new ArrayObject();
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $currentItemQuantity = $this->calculateCurrentCartQuantityForGivenSku(
                $cartChangeTransfer,
                $itemTransfer->getSku()
            );
             $currentItemQuantity += $itemTransfer->getQuantity();

            $isSellable = $this->availabilityFacade->isProductSellableForStore(
                $itemTransfer->getSku(),
                $currentItemQuantity,
                $storeTransfer
            );

            if (!$isSellable) {
                $stock = $this->availabilityFacade->calculateStockForProductWithStore($itemTransfer->getSku(), $storeTransfer);
                $cartPreCheckResponseTransfer->setIsSuccess(false);
                $messages[] = $this->createItemIsNotAvailableMessageTransfer($stock);
            }
        }

        $cartPreCheckResponseTransfer->setMessages($messages);

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param string $sku
     *
     * @return int
     */
    protected function calculateCurrentCartQuantityForGivenSku(CartChangeTransfer $cartChangeTransfer, $sku)
    {
        $quantity = 0;
        foreach ($cartChangeTransfer->getQuote()->getItems() as $itemTransfer) {
            if ($itemTransfer->getSku() !== $sku) {
                continue;
            }
            $quantity += $itemTransfer->getQuantity();
        }

        return $quantity;
    }

    /**
     * @param int $stock
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createItemIsNotAvailableMessageTransfer($stock)
    {
        $translationKey = $this->getTranslationKey($stock);

        return (new MessageTransfer())
            ->setValue($translationKey)
            ->setParameters([
                static::STOCK_TRANSLATION_PARAMETER => $stock,
            ]);
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
}
