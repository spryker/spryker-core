<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMeasurementUnit\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
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
    protected const PRODUCT_MEASUREMENT_SALES_UNIT_ID = 777;
    protected const NON_EXISTING_PRODUCT_MEASUREMENT_SALES_UNIT_ID = 9999;

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
    public function setUp(): void
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
    public function testCalculateQuantitySalesUnitValueCalculatesCorrectValues(int $quantity, float $conversion, int $precision, int $expectedResult): void
    {
        // Assign
        $quoteTransfer = (new QuoteTransfer())
            ->addItem(
                (new ItemTransfer())
                    ->setQuantity($quantity)
                    ->setQuantitySalesUnit(
                        (new ProductMeasurementSalesUnitTransfer())
                            ->setConversion($conversion)
                            ->setPrecision($precision)
                    )
            );

        // Act
        $actualResult = $this->productMeasurementUnitFacade->calculateQuantitySalesUnitValueInQuote($quoteTransfer);

        // Assert
        $this->assertSame($expectedResult, $actualResult->getItems()[0]->getQuantitySalesUnit()->getValue());
    }

    /**
     * @return array
     */
    public function calculateQuantityNormalizedSalesUnitValues(): array
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
    public function testFindProductMeasurementUnitEntitiesReturnsProductMeasurementUnitEntities(): void
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
    public function testGetSalesUnitsByIdProductRetrievesAllProductRelatedSalesUnits(): void
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
    public function testExpandCartChangeWithQuantitySalesUnitExpandsCartChangeWithQuantitySalesUnit(): void
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

    /**
     * @return void
     */
    public function testFindFilteredProductMeasurementSalesUnitTransfers(): void
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

        $filterTransfer = (new FilterTransfer())
            ->setOffset(0)
            ->setLimit(1);

        //Act
        $productMeasurementSalesUnitTransfers = $this->productMeasurementUnitFacade
            ->findFilteredProductMeasurementSalesUnitTransfers($filterTransfer);

        //Assert
        $this->assertCount(1, $productMeasurementSalesUnitTransfers);
    }

    /**
     * @return void
     */
    public function testCheckItemProductMeasurementSalesUnitWillReturnSuccessfulResponseWithEmptyCartChangeTransfer(): void
    {
        // Arrange
        $cartChangeTransfer = $this->tester->createEmptyCartChangeTransfer();

        // Act
        $cartPreCheckResponseTransfer = $this->productMeasurementUnitFacade->checkItemProductMeasurementSalesUnit($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertCount(0, $cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testCheckItemProductMeasurementSalesUnitWillReturnSuccessfulResponseWithCorrectSalesUnitId(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $sku = $productConcreteTransfer->getSku();
        $productMeasurementUnitTransfer = $this->tester->haveProductMeasurementUnit();
        $productMeasurementBaseUnitTransfer = $this->tester->haveProductMeasurementBaseUnit(
            $productConcreteTransfer->getFkProductAbstract(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit()
        );
        $this->tester->haveProductMeasurementSalesUnit(
            $productConcreteTransfer->getIdProductConcrete(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit(),
            $productMeasurementBaseUnitTransfer->getIdProductMeasurementBaseUnit(),
            [
                ProductMeasurementSalesUnitTransfer::ID_PRODUCT_MEASUREMENT_SALES_UNIT => static::PRODUCT_MEASUREMENT_SALES_UNIT_ID,
                ProductMeasurementSalesUnitTransfer::IS_DEFAULT => true,
                ProductMeasurementSalesUnitTransfer::PRODUCT_MEASUREMENT_BASE_UNIT => $productMeasurementBaseUnitTransfer->toArray(),
                ProductMeasurementSalesUnitTransfer::PRODUCT_MEASUREMENT_UNIT => $productMeasurementUnitTransfer->toArray(),
            ]
        );

        $cartChangeTransfer = $this->tester->createCartChangeTransferWithItem(
            static::PRODUCT_MEASUREMENT_SALES_UNIT_ID,
            $sku
        );

        // Act
        $cartPreCheckResponseTransfer = $this->productMeasurementUnitFacade->checkItemProductMeasurementSalesUnit($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertCount(0, $cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testCheckItemProductMeasurementSalesUnitWillReturnResponseWithErrorWithWrongSalesUnitId(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $sku = $productConcreteTransfer->getSku();
        $productMeasurementUnitTransfer = $this->tester->haveProductMeasurementUnit();
        $productMeasurementBaseUnitTransfer = $this->tester->haveProductMeasurementBaseUnit(
            $productConcreteTransfer->getFkProductAbstract(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit()
        );
        $this->tester->haveProductMeasurementSalesUnit(
            $productConcreteTransfer->getIdProductConcrete(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit(),
            $productMeasurementBaseUnitTransfer->getIdProductMeasurementBaseUnit(),
            [
                ProductMeasurementSalesUnitTransfer::IS_DEFAULT => true,
                ProductMeasurementSalesUnitTransfer::PRODUCT_MEASUREMENT_BASE_UNIT => $productMeasurementBaseUnitTransfer->toArray(),
                ProductMeasurementSalesUnitTransfer::PRODUCT_MEASUREMENT_UNIT => $productMeasurementUnitTransfer->toArray(),
            ]
        );

        $cartChangeTransfer = $this->tester->createCartChangeTransferWithItem(
            static::NON_EXISTING_PRODUCT_MEASUREMENT_SALES_UNIT_ID,
            $sku
        );

        // Act
        $cartPreCheckResponseTransfer = $this->productMeasurementUnitFacade->checkItemProductMeasurementSalesUnit($cartChangeTransfer);

        // Assert
        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertCount(1, $cartPreCheckResponseTransfer->getMessages());
    }
}
