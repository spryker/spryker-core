<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMeasurementUnit;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductMeasurementUnitBusinessTester extends Actor
{
    use _generated\ProductMeasurementUnitBusinessTesterActions;

    /**
     * Define custom actions here
     */

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
     * @param int $idProductMeasurementSalesUnit
     * @param string $sku
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function addSkuToCartChangeTransfer(
        CartChangeTransfer $cartChangeTransfer,
        int $idProductMeasurementSalesUnit,
        $sku,
        $quantity = 1
    ): CartChangeTransfer {
        $quantitySalesUnit = new ProductMeasurementSalesUnitTransfer();
        $quantitySalesUnit->setIdProductMeasurementSalesUnit($idProductMeasurementSalesUnit);
        $cartChangeTransfer->addItem(
            (new ItemTransfer())
                ->setSku($sku)
                ->setQuantity($quantity)
                ->setQuantitySalesUnit($quantitySalesUnit)
        );

        return $cartChangeTransfer;
    }
}
