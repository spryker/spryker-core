<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductQuantity;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductQuantityTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductQuantityBusinessTester extends Actor
{
    use _generated\ProductQuantityBusinessTesterActions;

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function createProductWithProductQuantity(): ProductConcreteTransfer
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
    public function createProductWithSpecificProductQuantity(?int $min, ?int $max, ?int $interval): ProductConcreteTransfer
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
    public function createEmptyCartChangeTransfer(): CartChangeTransfer
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
    public function addSkuToCartChangeTransferQuote(CartChangeTransfer $cartChangeTransfer, string $sku, int $quantity): CartChangeTransfer
    {
        $cartChangeTransfer->getQuote()->addItem(
            (new ItemTransfer())
                ->setSku($sku)
                ->setGroupKey($sku)
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
    public function addSkuToCartChangeTransfer(CartChangeTransfer $cartChangeTransfer, string $sku, int $quantity): CartChangeTransfer
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
    public function addEmptyItemTransferToCartChangeTransfer(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        $cartChangeTransfer->addItem(
            (new ItemTransfer())
        );

        return $cartChangeTransfer;
    }

    /**
     * @param string $sku
     * @param string $groupKey
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function createItemTransferWithNormalizableQuantity(string $sku, string $groupKey, int $quantity): ItemTransfer
    {
        return (new ItemBuilder([
            ItemTransfer::SKU => $sku,
            ItemTransfer::GROUP_KEY => $groupKey,
            ItemTransfer::QUANTITY => $quantity,
            ItemTransfer::NORMALIZABLE_FIELDS => ['quantity'],
        ]))->build();
    }
}
