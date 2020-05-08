<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMeasurementUnitsRestApi;

use Codeception\Actor;
use Generated\Shared\DataBuilder\CartItemRequestBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\PersistentCartChangeBuilder;
use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Spryker\DecimalObject\Decimal;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\ProductMeasurementUnitsRestApi\Business\ProductMeasurementUnitsRestApiFacade getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductMeasurementUnitsRestApiBusinessTester extends Actor
{
    use _generated\ProductMeasurementUnitsRestApiBusinessTesterActions;

    public const PRODUCT_CONCRETE_SKU = 'PRODUCT_CONCRETE_SKU';
    public const DIFFERENT_PRODUCT_CONCRETE_SKU = 'DIFFERENT_PRODUCT_CONCRETE_SKU';
    public const ID_PRODUCT_MEASUREMENT_SALES_UNIT_ID = '1';

    /**
     * @return \Generated\Shared\Transfer\CartItemRequestTransfer
     */
    public function buildCartItemRequestTransferWithSalesUnitData(): CartItemRequestTransfer
    {
        $productMeasurementSalesUnitTransfer = $this->createProductMeasurementSalesUnitTransfer(
            static::ID_PRODUCT_MEASUREMENT_SALES_UNIT_ID
        );

        return (new CartItemRequestBuilder(
            [
                'sku' => static::PRODUCT_CONCRETE_SKU,
                'idProductMeasurementSalesUnit' => $productMeasurementSalesUnitTransfer->getIdProductMeasurementSalesUnit(),
                'amount' => new Decimal(1000),
            ]
        ))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\CartItemRequestTransfer
     */
    public function buildCartItemRequestTransferWithOutSalesUnitData(): CartItemRequestTransfer
    {
        return (new CartItemRequestBuilder(['sku' => static::PRODUCT_CONCRETE_SKU]))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function buildPersistentCartChangeTransfer(): PersistentCartChangeTransfer
    {
        $itemBuilder = new ItemBuilder(['sku' => static::PRODUCT_CONCRETE_SKU]);

        return (new PersistentCartChangeBuilder())
            ->withItem($itemBuilder)
            ->build();
    }

    /**
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function buildPersistentCartChangeTransferWithDifferentSku(): PersistentCartChangeTransfer
    {
        $itemBuilder = new ItemBuilder(['sku' => static::DIFFERENT_PRODUCT_CONCRETE_SKU]);

        return (new PersistentCartChangeBuilder())
            ->withItem($itemBuilder)
            ->build();
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
}
