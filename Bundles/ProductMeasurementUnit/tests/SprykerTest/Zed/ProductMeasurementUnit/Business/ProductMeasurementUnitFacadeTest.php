<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMeasurementUnit\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;

/**
 * Auto-generated group annotations
 *
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
    public function testExpandItemGroupKeyWithSalesUnitReturnsOriginalGroupKeyWhenNoSalesUnitIsDefined(): void
    {
        // Assign
        $dummyGroupKey = 'GROUP_KEY_DUMMY';
        $expectedResult = $dummyGroupKey;

        $itemTransfer = (new ItemTransfer())
            ->setQuantitySalesUnit(null)
            ->setGroupKey($dummyGroupKey);

        // Act
        $actualResult = $this->productMeasurementUnitFacade->expandItemGroupKeyWithQuantitySalesUnit($itemTransfer);

        // Assert
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @return void
     */
    public function testExpandItemGroupKeyWithSalesUnitReturnsExpandedGroupKeyWhenSalesUnitIsDefined(): void
    {
        // Assign
        $dummyGroupKey = 'GROUP_KEY_DUMMY';
        $dummySalesUnitId = 5;
        $expectedPregMatch = sprintf('/%s.*%s/', $dummyGroupKey, $dummySalesUnitId);

        $itemTransfer = (new ItemTransfer())
            ->setQuantitySalesUnit((new ProductMeasurementSalesUnitTransfer())->setIdProductMeasurementSalesUnit($dummySalesUnitId))
            ->setGroupKey($dummyGroupKey);

        // Act
        $actualResult = $this->productMeasurementUnitFacade->expandItemGroupKeyWithQuantitySalesUnit($itemTransfer);

        // Assert
        $this->assertRegExp($expectedPregMatch, $actualResult);
    }

    /**
     * @dataProvider calculateQuantityNormalizedSalesUnitValues
     *
     * @param float $quantity
     * @param float $conversion
     * @param int $precision
     * @param float $expectedResult
     *
     * @return void
     */
    public function testCalculateQuantityNormalizedSalesUnitValueCalculatesCorrectValues($quantity, $conversion, $precision, $expectedResult)
    {
        // Assign
        $itemTransfer = (new ItemTransfer())
            ->setQuantity($quantity)
            ->setQuantitySalesUnit(
                (new ProductMeasurementSalesUnitTransfer())
                    ->setConversion($conversion)
                    ->setPrecision($precision)
            );

        // Act
        $actualResult = $this->productMeasurementUnitFacade->calculateQuantityNormalizedSalesUnitValue($itemTransfer);

        // Assert
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @return array
     */
    public function calculateQuantityNormalizedSalesUnitValues()
    {
        // round(1st / 2nd * 3rd) = 4th
        return [
            '7 breads in cart represented as 56 kg (1 qty = 1.25kg)' => [7, 1.25, 1000, 5600.0],
            '7 breads in cart represented as 5.6 kg (1 qty = 1.25kg)' => [7, 1.25, 100, 560.0],
            '7 breads in cart represented as 0.56 kg (1 qty = 1.25kg)' => [7, 1.25, 10, 56.0],
            '7 breads in cart represented as 0.06 kg (1 qty = 1.25kg)' => [7, 1.25, 1, 5.6],
            '10 breads in cart represented as 0.06 kg (1 qty = 1.25kg)' => [10, 5, 1, 2.0],
            '13 breads in cart represented as 1.857 kg (1 qty = 7kg)' => [13, 7, 1000, 1857.1428571428571],
            '13 breads in cart represented as 1.86 kg (1 qty = 0.7kg)' => [13, 7, 100, 185.71428571428572],
            '13 breads in cart represented as 0.019 kg (1 qty = 0.07kg)' => [13, 7, 10, 18.571428571428573],
            '13 breads in cart represented as 0.002 kg (1 qty = 0.007kg)' => [13, 7, 1, 1.8571428571428572],
            'quarter bread in cart represented as 0 kg (1 qty = 1kg)' => [0.25, 1, 1, 0.25],
            'quarter bread in cart represented as 0.25 kg (1 qty = 1kg)' => [0.25, 1, 100, 25.0],
            'half bread in cart represented as 1 kg (1 qty = 1kg)' => [0.5, 1, 1, 0.5],
        ];
    }

    /**
     * @return void
     */
    public function testFindProductMeasurementUnitEntitiesReturnsProductMeasurementUnitEntities()
    {
        // Assign
        $code = 'MYCODE' . random_int(1, 100);
        $productMeasurementUnitTransfer = $this->tester->haveProductMeasurementUnit([
            SpyProductMeasurementUnitEntityTransfer::CODE => $code,
        ]);
        $expectedCode = $code;

        // Act
        $productMeasurementUnitTransfer = $this->productMeasurementUnitFacade->findProductMeasurementUnitTransfers([$productMeasurementUnitTransfer->getIdProductMeasurementUnit()]);
        $actualCode = $productMeasurementUnitTransfer[0]->getCode();

        // Assert
        $this->assertSame($expectedCode, $actualCode);
    }

    /**
     * @return void
     */
    public function testGetSalesUnitsByIdProductRetrievesAllProductRelatedSalesUnits()
    {
        // Assign
        $code = 'MYCODE' . random_int(1, 100);
        $productTransfer = $this->tester->haveProduct();
        $productMeasurementUnitTransfer = $this->tester->haveProductMeasurementUnit([
            SpyProductMeasurementUnitEntityTransfer::CODE => $code,
        ]);
        $productMeasurementBaseUnitTransfer = $this->tester->haveProductMeasurementBaseUnit(
            $productTransfer->getFkProductAbstract(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit()
        );

        $expectedSalesUnitIds = [];
        for ($i = 0; $i < 5; $i++) {
            $productMeasurementSalesUnitTransfer = $this->tester->haveProductMeasurementSalesUnit(
                $productTransfer->getIdProductConcrete(),
                $productMeasurementUnitTransfer->getIdProductMeasurementUnit(),
                $productMeasurementBaseUnitTransfer->getIdProductMeasurementBaseUnit()
            );

            $expectedSalesUnitIds[] = $productMeasurementSalesUnitTransfer->getIdProductMeasurementSalesUnit();
        }
        sort($expectedSalesUnitIds);

        // Act
        $salesUnitTransfers = $this->productMeasurementUnitFacade->getSalesUnitsByIdProduct($productTransfer->getIdProductConcrete());
        $actualSalesUnitIds = [];
        foreach ($salesUnitTransfers as $salesUnitTransfer) {
            $actualSalesUnitIds[] = $salesUnitTransfer->getIdProductMeasurementSalesUnit();
        }
        sort($actualSalesUnitIds);

        // Assert
        $this->assertEquals($expectedSalesUnitIds, $actualSalesUnitIds);
    }

    /**
     * @return void
     */
    public function testExpandCartChangeWithQuantitySalesUnitExpandsCartChangeWithQuantitySalesUnit()
    {
        // Assign
        $code = 'MYCODE' . random_int(1, 100);
        $productTransfer = $this->tester->haveProduct();
        $productMeasurementUnitTransfer = $this->tester->haveProductMeasurementUnit([
            SpyProductMeasurementUnitEntityTransfer::CODE => $code,
        ]);

        $productMeasurementBaseUnitTransfer = $this->tester->haveProductMeasurementBaseUnit(
            $productTransfer->getFkProductAbstract(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit()
        );

        $productMeasurementSalesUnitTransfer = $this->tester->haveProductMeasurementSalesUnit(
            $productTransfer->getIdProductConcrete(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit(),
            $productMeasurementBaseUnitTransfer->getIdProductMeasurementBaseUnit()
        );

        $cartChangeTransfer = $this->tester->createEmptyCartChangeTransfer();
        $cartChangeTransfer = $this->tester->addSkuToCartChangeTransfer(
            $cartChangeTransfer,
            $productMeasurementSalesUnitTransfer->getIdProductMeasurementSalesUnit(),
            $productTransfer->getSku()
        );

        //Act
        $cartChangeTransfer = $this->productMeasurementUnitFacade->expandCartChangeWithQuantitySalesUnit($cartChangeTransfer);

        //Assert
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $this->assertNotNull($itemTransfer->getQuantitySalesUnit()->getConversion());
        }
    }

    /**
     * @return void
     */
    public function testGetProductMeasurementSalesUnitTransfer(): void
    {
        // Assign
        $code = 'MYCODE' . random_int(1, 100);

        $productTransfer = $this->tester->haveProduct();
        $productMeasurementUnitTransfer = $this->tester->haveProductMeasurementUnit([
            SpyProductMeasurementUnitEntityTransfer::CODE => $code,
        ]);

        $productMeasurementBaseUnitTransfer = $this->tester->haveProductMeasurementBaseUnit(
            $productTransfer->getFkProductAbstract(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit()
        );

        $productMeasurementSalesUnitTransfer = $this->tester->haveProductMeasurementSalesUnit(
            $productTransfer->getIdProductConcrete(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit(),
            $productMeasurementBaseUnitTransfer->getIdProductMeasurementBaseUnit()
        );

        $productMeasurementSalesUnitTransfer = $this->productMeasurementUnitFacade->getProductMeasurementSalesUnitTransfer($productMeasurementSalesUnitTransfer->getIdProductMeasurementSalesUnit());

        $this->assertInstanceOf(ProductMeasurementSalesUnitTransfer::class, $productMeasurementSalesUnitTransfer);
    }

    /**
     * @return void
     */
    public function testExpandOrderWithQuantitySalesUnit(): void
    {
        // Assign
        $orderTransfer = (new OrderTransfer())
            ->setIdSalesOrder(1);

        $orderTransfer = $this->productMeasurementUnitFacade
            ->expandOrderWithQuantitySalesUnit($orderTransfer);

        $this->assertInstanceOf(OrderTransfer::class, $orderTransfer);
    }

    /**
     * @return void
     */
    public function testExpandSalesOrderItem(): void
    {
        // Assign
        $code = 'MYCODE' . random_int(1, 100);
        $productTransfer = $this->tester->haveProduct();
        $productMeasurementUnitTransfer = $this->tester->haveProductMeasurementUnit([
            SpyProductMeasurementUnitEntityTransfer::CODE => $code,
        ]);
        $productMeasurementBaseUnitTransfer = $this->tester->haveProductMeasurementBaseUnit(
            $productTransfer->getFkProductAbstract(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit()
        );
        $productMeasurementBaseUnitTransfer->setProductMeasurementUnit($productMeasurementUnitTransfer);

        $productMeasurementSalesUnitTransfer = $this->tester->haveProductMeasurementSalesUnit(
            $productTransfer->getIdProductConcrete(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit(),
            $productMeasurementBaseUnitTransfer->getIdProductMeasurementBaseUnit()
        );

        $productMeasurementSalesUnitTransfer->setProductMeasurementUnit($productMeasurementUnitTransfer);
        $productMeasurementSalesUnitTransfer->setProductMeasurementBaseUnit($productMeasurementBaseUnitTransfer);

        $quantitySalesUnitTransfer = (new ProductMeasurementSalesUnitTransfer())->fromArray($productMeasurementSalesUnitTransfer->toArray(), true);
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantitySalesUnit($quantitySalesUnitTransfer);

        //Act
        $salesOrderItemEntity = $this->productMeasurementUnitFacade
            ->expandSalesOrderItem($itemTransfer, new SpySalesOrderItemEntityTransfer());

        //Assert
        $this->assertSame($productMeasurementUnitTransfer->getName(), $salesOrderItemEntity->getQuantityMeasurementUnitName());
        $this->assertSame($productMeasurementUnitTransfer->getName(), $salesOrderItemEntity->getQuantityBaseMeasurementUnitName());
        $this->assertSame($quantitySalesUnitTransfer->getPrecision(), $salesOrderItemEntity->getQuantityMeasurementUnitPrecision());
        $this->assertSame($quantitySalesUnitTransfer->getConversion(), $salesOrderItemEntity->getQuantityMeasurementUnitConversion());
    }

    /**
     * @return void
     */
    public function testTranslateProductMeasurementSalesUnit(): void
    {
        // Assign
        $code = 'MYCODE' . random_int(1, 100);
        $productTransfer = $this->tester->haveProduct();
        $productMeasurementUnitTransfer = $this->tester->haveProductMeasurementUnit([
            SpyProductMeasurementUnitEntityTransfer::CODE => $code,
        ]);

        $productMeasurementBaseUnitTransfer = $this->tester->haveProductMeasurementBaseUnit(
            $productTransfer->getFkProductAbstract(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit()
        );

        $spyProductMeasurementSalesUnitTransfer = $this->tester->haveProductMeasurementSalesUnit(
            $productTransfer->getIdProductConcrete(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit(),
            $productMeasurementBaseUnitTransfer->getIdProductMeasurementBaseUnit()
        );

        $productMeasurementSalesUnitTransfer = $this->tester
            ->createProductMeasurementSalesUnitTransfer($spyProductMeasurementSalesUnitTransfer->getIdProductMeasurementSalesUnit());

        $productMeasurementSalesUnitTransfer = $this->productMeasurementUnitFacade
            ->translateProductMeasurementSalesUnit($productMeasurementSalesUnitTransfer);

        $this->assertInstanceOf(ProductMeasurementSalesUnitTransfer::class, $productMeasurementSalesUnitTransfer);
    }
}
