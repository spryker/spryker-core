<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMeasurementUnit\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\OrderBuilder;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\ProductMeasurementUnit\ProductMeasurementUnitBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductMeasurementUnit
 * @group Business
 * @group HydrateCartReorderItemsWithQuantitySalesUnitTest
 * Add your own group annotations below this line
 */
class HydrateCartReorderItemsWithQuantitySalesUnitTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductMeasurementUnit\ProductMeasurementUnitBusinessTester
     */
    protected ProductMeasurementUnitBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldAddReorderItemsWithQuantitySalesUnitWhenItemWasNotAddedToReorderItems(): void
    {
        // Arrange
        $orderItemTransfers = new ArrayObject([
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                ItemTransfer::QUANTITY_SALES_UNIT => null,
            ]))->build(),
            $this->tester->createItemWithQuantitySalesUnit([
                ProductMeasurementSalesUnitTransfer::IS_DEFAULT => true,
            ])->setIdSalesOrderItem(2),
        ]);
        $orderTransfer = (new OrderBuilder([
            OrderTransfer::STORE => $this->tester->haveStore()->getNameOrFail(),
        ]))->build();
        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrder($orderTransfer)
            ->setOrderItems($orderItemTransfers);

        // Act
        $cartReorderTransfer = $this->tester->getFacade()->hydrateCartReorderItemsWithQuantitySalesUnit($cartReorderTransfer);

        // Assert
        $this->assertCount(1, $cartReorderTransfer->getReorderItems());

        $reorderItemTransfer = $cartReorderTransfer->getReorderItems()->offsetGet(1);
        $this->assertNotNull($reorderItemTransfer->getQuantitySalesUnit());
        $this->assertNotNull($reorderItemTransfer->getQuantitySalesUnitOrFail()->getIdProductMeasurementSalesUnit());
        $this->assertSame($orderItemTransfers[1]->getIdSalesOrderItemOrFail(), $reorderItemTransfer->getIdSalesOrderItem());
        $this->assertSame($orderItemTransfers[1]->getSkuOrFail(), $reorderItemTransfer->getSku());
        $this->assertSame($orderItemTransfers[1]->getQuantityOrFail(), $reorderItemTransfer->getQuantity());
    }

    /**
     * @return void
     */
    public function testShouldAddQuantitySalesUnitToReorderItemWhenItemWasPreviouslyAddedToReorderItems(): void
    {
        // Arrange
        $orderItemTransfers = new ArrayObject([
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                ItemTransfer::QUANTITY_SALES_UNIT => null,
            ]))->build(),
            $this->tester->createItemWithQuantitySalesUnit([
                ProductMeasurementSalesUnitTransfer::IS_DEFAULT => true,
            ])->setIdSalesOrderItem(2),
        ]);
        $reorderItemTransfer = (new ItemTransfer())
            ->setIdSalesOrderItem($orderItemTransfers->offsetGet(1)->getIdSalesOrderItemOrFail())
            ->setSku($orderItemTransfers->offsetGet(1)->getSkuOrFail())
            ->setQuantity($orderItemTransfers->offsetGet(1)->getQuantityOrFail());
        $orderTransfer = (new OrderBuilder([
            OrderTransfer::STORE => $this->tester->haveStore()->getNameOrFail(),
        ]))->build();
        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrder($orderTransfer)
            ->setOrderItems($orderItemTransfers)
            ->addReorderItem($reorderItemTransfer);

        // Act
        $cartReorderTransfer = $this->tester->getFacade()->hydrateCartReorderItemsWithQuantitySalesUnit($cartReorderTransfer);

        // Assert
        $this->assertCount(1, $cartReorderTransfer->getReorderItems());

        $reorderItemTransfer = $cartReorderTransfer->getReorderItems()->offsetGet(0);
        $this->assertNotNull($reorderItemTransfer->getQuantitySalesUnit());
        $this->assertNotNull($reorderItemTransfer->getQuantitySalesUnitOrFail()->getIdProductMeasurementSalesUnit());
    }

    /**
     * @return void
     */
    public function testShouldDoNothingWhenNoItemsWithQuantitySalesUnitProvided(): void
    {
        // Arrange
        $orderItemTransfers = new ArrayObject([
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                ItemTransfer::QUANTITY_SALES_UNIT => null,
            ]))->build(),
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 2,
                ItemTransfer::QUANTITY_SALES_UNIT => null,
            ]))->build(),
        ]);
        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrderItems($orderItemTransfers);

        // Act
        $cartReorderTransfer = $this->tester->getFacade()->hydrateCartReorderItemsWithQuantitySalesUnit($cartReorderTransfer);

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
        $orderTransfer = (new OrderBuilder([
            OrderTransfer::STORE => $this->tester->haveStore()->getNameOrFail(),
        ]))->build();

        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrder($orderTransfer)
            ->addOrderItem($itemTransfer);

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage($exceptionMessage);

        // Act
        $this->tester->getFacade()->hydrateCartReorderItemsWithQuantitySalesUnit($cartReorderTransfer);
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\ItemTransfer|string>>
     */
    protected function throwsNullValueExceptionWhenRequiredItemPropertyIsNotSetDataProvider(): array
    {
        return [
            'idSalesOrderItem is not provided' => [
                (new ItemBuilder([
                    ItemTransfer::ID => 11,
                    ItemTransfer::ID_SALES_ORDER_ITEM => null,
                    ItemTransfer::QUANTITY_SALES_UNIT => [],
                ]))->build(),
                sprintf('Property "idSalesOrderItem" of transfer `%s` is null.', ItemTransfer::class),
            ],
            'sku is not provided' => [
                (new ItemBuilder([
                    ItemTransfer::ID => 11,
                    ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                    ItemTransfer::SKU => null,
                    ItemTransfer::QUANTITY_SALES_UNIT => [],
                ]))->build(),
                sprintf('Property "sku" of transfer `%s` is null.', ItemTransfer::class),
            ],
            'quantity is not provided' => [
                (new ItemBuilder([
                    ItemTransfer::ID => 11,
                    ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                    ItemTransfer::QUANTITY => null,
                    ItemTransfer::QUANTITY_SALES_UNIT => [],
                ]))->build(),
                sprintf('Property "quantity" of transfer `%s` is null.', ItemTransfer::class),
            ],
            'ID is not provided' => [
                (new ItemBuilder([
                    ItemTransfer::ID => null,
                    ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                    ItemTransfer::QUANTITY => 1,
                    ItemTransfer::QUANTITY_SALES_UNIT => [],
                ]))->build(),
                sprintf('Property "id" of transfer `%s` is null.', ItemTransfer::class),
            ],
        ];
    }
}
