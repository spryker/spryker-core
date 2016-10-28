<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityCartConnector\Business\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToAvailabilityInterface;

class CheckCartAvailability
{

    const CART_PRE_CHECK_AVAILABILITY_FAILED = 'cart.pre.check.availability.failed';
    const STOCK_TRANSLATION_KEY = 'stock';

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

        $messages = new \ArrayObject();
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
             $currentItemQuantity = $this->calculateCurrentCartQuantityForGivenSku(
                 $cartChangeTransfer,
                 $itemTransfer->getSku()
             );
             $currentItemQuantity += $itemTransfer->getQuantity();

             $isSellable = $this->availabilityFacade->isProductSellable(
                 $itemTransfer->getSku(),
                 $currentItemQuantity
             );

            if (!$isSellable) {
                $stock = $this->availabilityFacade->calculateStockForProduct($itemTransfer->getSku());
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
        $messageTranfer = new MessageTransfer();
        $messageTranfer->setValue(self::CART_PRE_CHECK_AVAILABILITY_FAILED);
        $messageTranfer->setParameters([self::STOCK_TRANSLATION_KEY => $stock]);

        return $messageTranfer;
    }

}
