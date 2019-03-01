<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductQuantity;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductQuantityTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductQuantityBusinessTester extends Actor
{
    use _generated\ProductQuantityBusinessTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function createProductWithProductQuantity()
    {
        $productTransfer = $this->haveProduct();
        $this->haveProductQuantity($productTransfer->getIdProductConcrete());

        return $productTransfer;
    }

    /**
     * @param int|null $min
     * @param int|null $max
     * @param int|null $interval
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function createProductWithSpecificProductQuantity($min, $max, $interval)
    {
        $productQuantityOverride = [
            ProductQuantityTransfer::QUANTITY_INTERVAL => $interval,
            ProductQuantityTransfer::QUANTITY_MIN => $min,
            ProductQuantityTransfer::QUANTITY_MAX => $max,
        ];

        $productTransfer = $this->haveProduct();
        $this->haveProductQuantity($productTransfer->getIdProductConcrete(), $productQuantityOverride);

        return $productTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function createEmptyCartChangeTransfer()
    {
        return (new CartChangeTransfer())
            ->setQuote(
                (new QuoteTransfer())
                    ->setItems(new ArrayObject([]))
            )
            ->setItems(new ArrayObject([]));
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param string $sku
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function addSkuToCartChangeTransferQuote(CartChangeTransfer $cartChangeTransfer, $sku, $quantity)
    {
        $cartChangeTransfer->getQuote()->addItem(
            (new ItemTransfer())
                ->setSku($sku)
                ->setQuantity($quantity)
        );

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param string $sku
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function addSkuToCartChangeTransfer(CartChangeTransfer $cartChangeTransfer, $sku, $quantity)
    {
        $cartChangeTransfer->addItem(
            (new ItemTransfer())
                ->setSku($sku)
                ->setQuantity($quantity)
        );

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function addEmptyItemTransferToCartChangeTransfer(CartChangeTransfer $cartChangeTransfer)
    {
        $cartChangeTransfer->addItem(
            (new ItemTransfer())
        );

        return $cartChangeTransfer;
    }
}
