<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMeasurementUnit\Business;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductMeasurementUnit
 * @group Business
 * @group Facade
 * @group ProductMeasurementUnitFacadeTest
 * Add your own group annotations below this line
 */
class ProductMeasurementUnitFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductMeasurementUnit\ProductMeasurementUnitBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Business\ProductMeasurementUnitFacadeInterface
     */
    protected $productMeasurementUnitFacade;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->productMeasurementUnitFacade = $this->tester->getLocator()->productMeasurementUnit()->facade();
    }

    /**
     * @return void
     */
    public function testGetBaseUnitByIdProduct(): void
    {
        // Assign
//        $product = $this->tester->haveProduct();
//        $idProductMeasurementUnit = $this->tester->haveProductMeasurementUnit("METR");
//        $idProductMeasurementBaseUnit = $this->tester->haveProductMeasurementBaseUnit($product->getFkProductAbstract(), $idProductMeasurementUnit);
//        $expectedResult = (new SpyProductMeasurementBaseUnitEntityTransfer())
//            ->setIdProductMeasurementBaseUnit($idProductMeasurementBaseUnit);

        // Act
//        $actualResult = $this->productMeasurementUnitFacade->getBaseUnitByIdProduct($product->getIdProductConcrete());

        // Assert
//        $this->assertSame($expectedResult->toArray(), $actualResult->toArray());
    }

    /**
     * @return void
     */
    public function testGetSalesUnitsByIdProduct(): void
    {
        // Assign

        // Act
        //$this->productMeasurementUnitFacade->getSalesUnitsByIdProduct();

        // Assert
    }

    /**
     * @return void
     */
    public function testValidateQuantitySalesUnitValues(): void
    {
        // Assign

        // Act
       // $this->productMeasurementUnitFacade->validateQuantitySalesUnitValues();

        // Assert
    }

    /**
     * @return void
     */
    public function testExpandItemGroupKeyWithSalesUnit(): void
    {
        // Assign

        // Act
       // $this->productMeasurementUnitFacade->expandItemGroupKeyWithSalesUnit();

        // Assert
    }

    /**
     * @return void
     */
    public function testCalculateQuantityNormalizedSalesUnitValue(): void
    {
        // Assign

        // Act
    //    $this->productMeasurementUnitFacade->calculateQuantityNormalizedSalesUnitValue();

        // Assert
    }

    /**
     * @return void
     */
    public function testGetSalesUnitEntity(): void
    {
        // Assign

        // Act
     //   $this->productMeasurementUnitFacade->getSalesUnitEntity();

        // Assert
    }
}
