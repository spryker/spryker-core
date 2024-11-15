<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Business\ProductOptionFacade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\ProductOption\ProductOptionBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOption
 * @group Business
 * @group ProductOptionFacade
 * @group HydrateCartReorderItemWithProductOptionsTest
 * Add your own group annotations below this line
 */
class HydrateCartReorderItemWithProductOptionsTest extends Unit
{
    /**
     * @var int
     */
    protected const TEST_ID_PRODUCT_OPTION_VALUE = 1;

    /**
     * @var \SprykerTest\Zed\ProductOption\ProductOptionBusinessTester
     */
    protected ProductOptionBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldAddReorderItemsWithIdProductOptionValueWhenItemWasNotAddedToReorderItems(): void
    {
        // Arrange
        $orderItemTransfers = new ArrayObject([
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                ItemTransfer::PRODUCT_OPTIONS => [],
            ]))->build(),
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 2,
                ItemTransfer::PRODUCT_OPTIONS => [
                    [ProductOptionTransfer::ID_PRODUCT_OPTION_VALUE => static::TEST_ID_PRODUCT_OPTION_VALUE],
                ],
            ]))->build(),
        ]);
        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrderItems($orderItemTransfers);

        // Act
        $cartReorderTransfer = $this->tester->getFacade()->hydrateCartReorderItemWithProductOptions($cartReorderTransfer);

        // Assert
        $this->assertCount(1, $cartReorderTransfer->getReorderItems());

        $reorderItemTransfer = $cartReorderTransfer->getReorderItems()->offsetGet(1);
        $this->assertCount(1, $reorderItemTransfer->getProductOptions());
        $this->assertSame(
            static::TEST_ID_PRODUCT_OPTION_VALUE,
            $reorderItemTransfer->getProductOptions()->offsetGet(0)->getIdProductOptionValue(),
        );
        $this->assertSame($orderItemTransfers[1]->getIdSalesOrderItemOrFail(), $reorderItemTransfer->getIdSalesOrderItem());
        $this->assertSame($orderItemTransfers[1]->getSkuOrFail(), $reorderItemTransfer->getSku());
        $this->assertSame($orderItemTransfers[1]->getQuantityOrFail(), $reorderItemTransfer->getQuantity());
    }

    /**
     * @return void
     */
    public function testShouldAddIdProductOptionValueToReorderItemWhenItemWasPreviouslyAddedToReorderItems(): void
    {
        // Arrange
        $orderItemTransfers = new ArrayObject([
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                ItemTransfer::PRODUCT_OPTIONS => [],
            ]))->build(),
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 2,
                ItemTransfer::PRODUCT_OPTIONS => [
                    [ProductOptionTransfer::ID_PRODUCT_OPTION_VALUE => static::TEST_ID_PRODUCT_OPTION_VALUE],
                ],
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
        $cartReorderTransfer = $this->tester->getFacade()->hydrateCartReorderItemWithProductOptions($cartReorderTransfer);

        // Assert
        $this->assertCount(1, $cartReorderTransfer->getReorderItems());

        $reorderItemTransfer = $cartReorderTransfer->getReorderItems()->offsetGet(0);
        $this->assertCount(1, $reorderItemTransfer->getProductOptions());
        $this->assertSame(
            static::TEST_ID_PRODUCT_OPTION_VALUE,
            $reorderItemTransfer->getProductOptions()->offsetGet(0)->getIdProductOptionValue(),
        );
    }

    /**
     * @return void
     */
    public function testShouldDoNothingWhenNoItemsWithProductOptionsProvided(): void
    {
        // Arrange
        $orderItemTransfers = new ArrayObject([
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                ItemTransfer::PRODUCT_OPTIONS => [],
            ]))->build(),
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 2,
                ItemTransfer::PRODUCT_OPTIONS => [],
            ]))->build(),
        ]);
        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrderItems($orderItemTransfers);

        // Act
        $cartReorderTransfer = $this->tester->getFacade()->hydrateCartReorderItemWithProductOptions($cartReorderTransfer);

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
        $this->tester->getFacade()->hydrateCartReorderItemWithProductOptions($cartReorderTransfer);
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
                    ItemTransfer::PRODUCT_OPTIONS => [
                        [ProductOptionTransfer::ID_PRODUCT_OPTION_VALUE => static::TEST_ID_PRODUCT_OPTION_VALUE],
                    ],
                ]))->build(),
                sprintf('Property "idSalesOrderItem" of transfer `%s` is null.', ItemTransfer::class),
            ],
            'sku is not provided' => [
                (new ItemBuilder([
                    ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                    ItemTransfer::SKU => null,
                    ItemTransfer::PRODUCT_OPTIONS => [
                        [ProductOptionTransfer::ID_PRODUCT_OPTION_VALUE => static::TEST_ID_PRODUCT_OPTION_VALUE],
                    ],
                ]))->build(),
                sprintf('Property "sku" of transfer `%s` is null.', ItemTransfer::class),
            ],
            'quantity is not provided' => [
                (new ItemBuilder([
                    ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                    ItemTransfer::QUANTITY => null,
                    ItemTransfer::PRODUCT_OPTIONS => [
                        [ProductOptionTransfer::ID_PRODUCT_OPTION_VALUE => static::TEST_ID_PRODUCT_OPTION_VALUE],
                    ],
                ]))->build(),
                sprintf('Property "quantity" of transfer `%s` is null.', ItemTransfer::class),
            ],
//            'idProductOptionValue is not provided' => [
//                (new ItemBuilder([
//                    ItemTransfer::ID_SALES_ORDER_ITEM => 1,
//                    ItemTransfer::PRODUCT_OPTIONS => [
//                        [ProductOptionTransfer::ID_PRODUCT_OPTION_VALUE => null],
//                    ],
//                ]))->build(),
//                sprintf('Property "idProductOptionValue" of transfer `%s` is null.', ProductOptionTransfer::class),
//            ],
        ];
    }
}
