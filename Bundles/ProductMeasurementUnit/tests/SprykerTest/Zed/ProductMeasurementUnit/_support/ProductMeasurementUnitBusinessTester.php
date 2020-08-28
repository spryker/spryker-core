<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMeasurementUnit;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer;
use Generated\Shared\Transfer\StoreTransfer;

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
 * @method \Spryker\Zed\ProductMeasurementUnit\Business\ProductMeasurementUnitFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductMeasurementUnitBusinessTester extends Actor
{
    use _generated\ProductMeasurementUnitBusinessTesterActions;

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
        string $sku,
        int $quantity = 1
    ): CartChangeTransfer {
        $quantitySalesUnit = $this->createProductMeasurementSalesUnitTransfer($idProductMeasurementSalesUnit);

        $cartChangeTransfer->addItem(
            (new ItemTransfer())
                ->setSku($sku)
                ->setQuantity($quantity)
                ->setQuantitySalesUnit($quantitySalesUnit)
        );

        return $cartChangeTransfer;
    }

    /**
     * @param int $idProductMeasurementSalesUnit
     * @param string $sku
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function createCartChangeTransferWithItem(
        int $idProductMeasurementSalesUnit,
        string $sku,
        int $quantity = 1
    ): CartChangeTransfer {
        $amountSalesUnit = $this->createProductMeasurementSalesUnitTransfer($idProductMeasurementSalesUnit);

        return (new CartChangeTransfer())->addItem(
            (new ItemTransfer())
                ->setSku($sku)
                ->setQuantity($quantity)
                ->setQuantitySalesUnit($amountSalesUnit)
        );
    }

    /**
     * @param int $idProductMeasurementSalesUnit
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    public function createProductMeasurementSalesUnitTransfer(
        int $idProductMeasurementSalesUnit
    ): ProductMeasurementSalesUnitTransfer {
        $productMeasurementUnit = (new ProductMeasurementUnitTransfer())
            ->setName('SalesUnitName');
        $productMeasurementBaseUnit = (new ProductMeasurementBaseUnitTransfer())
            ->setProductMeasurementUnit(
                (new ProductMeasurementUnitTransfer())->setName('BaseUnitName')
            );

        $quantitySalesUnit = new ProductMeasurementSalesUnitTransfer();
        $quantitySalesUnit->setIdProductMeasurementSalesUnit($idProductMeasurementSalesUnit)
            ->setProductMeasurementUnit($productMeasurementUnit)
            ->setProductMeasurementBaseUnit($productMeasurementBaseUnit);

        return $quantitySalesUnit;
    }

    /**
     * @param string $stateMachineProcessName
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createOrderWithProductMeasurementUnits(string $stateMachineProcessName): OrderTransfer
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();

        $quoteTransfer
            ->addItem($this->createItemWithQuantitySalesUnit())
            ->addItem($this->createItemWithQuantitySalesUnit());

        $quoteTransfer
            ->setCustomer($this->haveCustomer())
            ->setStore($this->haveStore([StoreTransfer::NAME => 'DE']));

        $saveOrderTransfer = $this->haveOrderFromQuote($quoteTransfer, $stateMachineProcessName);

        return (new OrderTransfer())
            ->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder())
            ->setOrderReference($saveOrderTransfer->getOrderReference())
            ->setStore($quoteTransfer->getStore()->getName())
            ->setCustomer($quoteTransfer->getCustomer())
            ->setItems($saveOrderTransfer->getOrderItems());
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemWithQuantitySalesUnit(): ItemTransfer
    {
        $productTransfer = $this->haveProduct();

        $productMeasurementUnitTransfer = $this->haveProductMeasurementUnit([
            SpyProductMeasurementUnitEntityTransfer::CODE => 'MYCODE' . random_int(1, 100),
        ]);

        $productMeasurementBaseUnitTransfer = $this->haveProductMeasurementBaseUnit(
            $productTransfer->getFkProductAbstract(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit()
        );

        $productMeasurementSalesUnitTransfer = $this->haveProductMeasurementSalesUnit(
            $productTransfer->getIdProductConcrete(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit(),
            $productMeasurementBaseUnitTransfer->getIdProductMeasurementBaseUnit()
        );

        $quantitySalesUnit = $this->createProductMeasurementSalesUnitTransfer($productMeasurementSalesUnitTransfer->getIdProductMeasurementSalesUnit());

        return (new ItemBuilder())->build()
            ->setSku($productTransfer->getSku())
            ->setQuantitySalesUnit($quantitySalesUnit);
    }
}
