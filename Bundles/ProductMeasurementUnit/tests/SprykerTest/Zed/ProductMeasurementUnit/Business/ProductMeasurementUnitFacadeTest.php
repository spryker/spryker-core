<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMeasurementUnit\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer;

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
     * @param int $quantity
     * @param float $conversion
     * @param int $precision
     * @param int $expectedResult
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
            [7, 1.25, 1000, 5600],
            [7, 1.25, 100, 560],
            [7, 1.25, 10, 56],
            [7, 1.25, 1, 6],
            [10, 5, 1, 2],
            [13, 7, 1000, 1857],
            [13, 7, 100, 186],
            [13, 7, 10, 19],
            [13, 7, 1, 2],
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
}
