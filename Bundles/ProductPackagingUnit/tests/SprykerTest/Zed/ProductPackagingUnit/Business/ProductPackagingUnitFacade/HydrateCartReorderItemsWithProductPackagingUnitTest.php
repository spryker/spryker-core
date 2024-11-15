<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnit
 * @group Business
 * @group HydrateCartReorderItemsWithProductPackagingUnitTest
 * Add your own group annotations below this line
 */
class HydrateCartReorderItemsWithProductPackagingUnitTest extends Unit
{
    /**
     * @var int
     */
    protected const TEST_AMOUNT = 100;

    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected ProductPackagingUnitBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldAddReorderItemsWithAmountSalesUnitAndAmountWhenItemWasNotAddedToReorderItems(): void
    {
        // Arrange
        $leadProductConcreteTransfer = $this->tester->haveProduct();

        $productMeasurementUnitTransfer = $this->tester->haveProductMeasurementUnit([
            SpyProductMeasurementUnitEntityTransfer::CODE => 'MYCODE' . random_int(1, 100),
        ]);

        $productMeasurementBaseUnitTransfer = $this->tester->haveProductMeasurementBaseUnit(
            $leadProductConcreteTransfer->getFkProductAbstract(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit(),
        );

        $productMeasurementSalesUnitEntityTransfer = $this->tester->haveProductMeasurementSalesUnit(
            $leadProductConcreteTransfer->getIdProductConcrete(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit(),
            $productMeasurementBaseUnitTransfer->getIdProductMeasurementBaseUnit(),
            [ProductMeasurementSalesUnitTransfer::IS_DEFAULT => true],
        );

        $orderItemTransfers = new ArrayObject([
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                ItemTransfer::AMOUNT_SALES_UNIT => null,
            ]))->build(),
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 2,
                ItemTransfer::AMOUNT => static::TEST_AMOUNT,
                ItemTransfer::AMOUNT_SALES_UNIT => [],
                ItemTransfer::AMOUNT_LEAD_PRODUCT => $leadProductConcreteTransfer->toArray(),
            ]))->build(),
        ]);
        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrderItems($orderItemTransfers);

        // Act
        $cartReorderTransfer = $this->tester->getFacade()->hydrateCartReorderItemsWithProductPackagingUnit($cartReorderTransfer);

        // Assert
        $this->assertCount(1, $cartReorderTransfer->getReorderItems());

        $reorderItemTransfer = $cartReorderTransfer->getReorderItems()->offsetGet(1);
        $this->assertNotNull($reorderItemTransfer->getAmountSalesUnit());
        $this->assertSame(
            $productMeasurementSalesUnitEntityTransfer->getIdProductMeasurementSalesUnitOrFail(),
            $reorderItemTransfer->getAmountSalesUnitOrFail()->getIdProductMeasurementSalesUnit(),
        );
        $this->assertSame(static::TEST_AMOUNT, $reorderItemTransfer->getAmount()->toInt());
        $this->assertSame($orderItemTransfers[1]->getIdSalesOrderItemOrFail(), $reorderItemTransfer->getIdSalesOrderItem());
        $this->assertSame($orderItemTransfers[1]->getSkuOrFail(), $reorderItemTransfer->getSku());
        $this->assertSame($orderItemTransfers[1]->getQuantityOrFail(), $reorderItemTransfer->getQuantity());
    }

    /**
     * @return void
     */
    public function testShouldAddAmountSalesUnitAndAmountToReorderItemWhenItemWasPreviouslyAddedToReorderItems(): void
    {
        // Arrange
        $leadProductConcreteTransfer = $this->tester->haveProduct();

        $productMeasurementUnitTransfer = $this->tester->haveProductMeasurementUnit([
            SpyProductMeasurementUnitEntityTransfer::CODE => 'MYCODE' . random_int(1, 100),
        ]);

        $productMeasurementBaseUnitTransfer = $this->tester->haveProductMeasurementBaseUnit(
            $leadProductConcreteTransfer->getFkProductAbstract(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit(),
        );

        $productMeasurementSalesUnitEntityTransfer = $this->tester->haveProductMeasurementSalesUnit(
            $leadProductConcreteTransfer->getIdProductConcrete(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit(),
            $productMeasurementBaseUnitTransfer->getIdProductMeasurementBaseUnit(),
            [ProductMeasurementSalesUnitTransfer::IS_DEFAULT => true],
        );

        $orderItemTransfers = new ArrayObject([
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                ItemTransfer::AMOUNT_SALES_UNIT => null,
            ]))->build(),
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 2,
                ItemTransfer::AMOUNT => static::TEST_AMOUNT,
                ItemTransfer::AMOUNT_SALES_UNIT => [],
                ItemTransfer::AMOUNT_LEAD_PRODUCT => $leadProductConcreteTransfer->toArray(),
            ]))->build(),
        ]);
        $reorderItemTransfer = (new ItemTransfer())
            ->setIdSalesOrderItem($orderItemTransfers->offsetGet(1)->getIdSalesOrderItemOrFail())
            ->setSku($orderItemTransfers->offsetGet(1)->getSkuOrFail())
            ->setQuantity($orderItemTransfers->offsetGet(1)->getQuantityOrFail());
        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrderItems($orderItemTransfers)
            ->addReorderItem($reorderItemTransfer);

        // Act
        $cartReorderTransfer = $this->tester->getFacade()->hydrateCartReorderItemsWithProductPackagingUnit($cartReorderTransfer);

        // Assert
        $this->assertCount(1, $cartReorderTransfer->getReorderItems());

        $reorderItemTransfer = $cartReorderTransfer->getReorderItems()->offsetGet(0);
        $this->assertNotNull($reorderItemTransfer->getAmountSalesUnit());
        $this->assertSame(
            $productMeasurementSalesUnitEntityTransfer->getIdProductMeasurementSalesUnitOrFail(),
            $reorderItemTransfer->getAmountSalesUnitOrFail()->getIdProductMeasurementSalesUnit(),
        );
        $this->assertSame(static::TEST_AMOUNT, $reorderItemTransfer->getAmount()->toInt());
    }

    /**
     * @return void
     */
    public function testShouldDoNothingWhenNoItemsWithAmountSalesUnitProvided(): void
    {
        // Arrange
        $orderItemTransfers = new ArrayObject([
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                ItemTransfer::AMOUNT_SALES_UNIT => null,
            ]))->build(),
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 2,
                ItemTransfer::AMOUNT_SALES_UNIT => null,
            ]))->build(),
        ]);
        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrderItems($orderItemTransfers);

        // Act
        $cartReorderTransfer = $this->tester->getFacade()->hydrateCartReorderItemsWithProductPackagingUnit($cartReorderTransfer);

        // Assert
        $this->assertCount(0, $cartReorderTransfer->getReorderItems());
    }

    /**
     * @dataProvider throwsNullValueExceptionWhenRequiredItemPropertyIsNotSetDataProvider
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $exceptionMessage
     *
     * @return void
     */
    public function testThrowsNullValueExceptionWhenRequiredItemPropertyIsNotSet(ItemTransfer $itemTransfer, string $exceptionMessage): void
    {
        // Arrange
        $cartReorderTransfer = (new CartReorderTransfer())
            ->addOrderItem($itemTransfer);

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage($exceptionMessage);

        // Act
        $this->tester->getFacade()->hydrateCartReorderItemsWithProductPackagingUnit($cartReorderTransfer);
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\ItemTransfer|string>>
     */
    protected function throwsNullValueExceptionWhenRequiredItemPropertyIsNotSetDataProvider(): array
    {
        return [
            'idSalesOrderItem is not provided' => [
                (new ItemBuilder([
                    ItemTransfer::ID_SALES_ORDER_ITEM => null,
                    ItemTransfer::AMOUNT => static::TEST_AMOUNT,
                    ItemTransfer::AMOUNT_SALES_UNIT => [],
                ]))->build(),
                sprintf('Property "idSalesOrderItem" of transfer `%s` is null.', ItemTransfer::class),
            ],
            'sku is not provided' => [
                (new ItemBuilder([
                    ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                    ItemTransfer::SKU => null,
                    ItemTransfer::AMOUNT => static::TEST_AMOUNT,
                    ItemTransfer::AMOUNT_SALES_UNIT => [],
                ]))->build(),
                sprintf('Property "sku" of transfer `%s` is null.', ItemTransfer::class),
            ],
            'quantity is not provided' => [
                (new ItemBuilder([
                    ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                    ItemTransfer::QUANTITY => null,
                    ItemTransfer::AMOUNT => static::TEST_AMOUNT,
                    ItemTransfer::AMOUNT_SALES_UNIT => [],
                ]))->build(),
                sprintf('Property "quantity" of transfer `%s` is null.', ItemTransfer::class),
            ],
        ];
    }
}
