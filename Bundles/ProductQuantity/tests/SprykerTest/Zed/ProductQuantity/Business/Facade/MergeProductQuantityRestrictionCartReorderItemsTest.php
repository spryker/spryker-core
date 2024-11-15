<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductQuantity\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CartReorderBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\OrderBuilder;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\ProductQuantity\ProductQuantityBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductQuantity
 * @group Business
 * @group Facade
 * @group MergeProductQuantityRestrictionCartReorderItemsTest
 * Add your own group annotations below this line
 */
class MergeProductQuantityRestrictionCartReorderItemsTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_ITEM_GROUP_KEY = 'test-item-group-key';

    /**
     * @var string
     */
    protected const TEST_ITEM_SKU = 'test-sku';

    /**
     * @var \SprykerTest\Zed\ProductQuantity\ProductQuantityBusinessTester
     */
    protected ProductQuantityBusinessTester $tester;

    /**
     * @return void
     */
    public function testMergesQuantityOfItemsWithProductQuantityRestrictions(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->createProductWithSpecificProductQuantity(2, 10, 1);

        $cartReorderTransfer = (new CartReorderBuilder([
            CartReorderTransfer::ORDER => [
                OrderTransfer::ITEMS => [
                    $this->createOrderItemTransfer(1, $productConcreteTransfer->getSkuOrFail())->toArray(),
                    $this->createOrderItemTransfer(2, $productConcreteTransfer->getSkuOrFail())->toArray(),
                ],
            ],
            CartReorderTransfer::ORDER_ITEMS => [
                $this->createOrderItemTransfer(1, $productConcreteTransfer->getSkuOrFail())->toArray(),
                $this->createOrderItemTransfer(2, $productConcreteTransfer->getSkuOrFail())->toArray(),
            ],
        ]))->build();
        $cartReorderRequestTransfer = new CartReorderRequestTransfer();

        // Act
        $cartReorderTransfer = $this->tester->getFacade()->mergeProductQuantityRestrictionCartReorderItems(
            $cartReorderRequestTransfer,
            $cartReorderTransfer,
        );

        // Assert
        $this->assertCount(1, $cartReorderTransfer->getOrderItems());
        $this->assertCount(2, $cartReorderTransfer->getOrderOrFail()->getItems());

        $orderItemTransfer = $cartReorderTransfer->getOrderItems()->getIterator()->current();
        $this->assertSame(2, $orderItemTransfer->getQuantity());
    }

    /**
     * @return void
     */
    public function testDoesNothingWhenNoItemsWithProductQuantityRestrictionProvided(): void
    {
        // Arrange
        $cartReorderTransfer = (new CartReorderBuilder())
            ->withOrder((new OrderBuilder())->withItem()->withAnotherItem())
            ->withOrderItem()
            ->withAnotherOrderItem()
            ->build();
        $cartReorderRequestTransfer = new CartReorderRequestTransfer();

        // Act
        $cartReorderTransfer = $this->tester->getFacade()->mergeProductQuantityRestrictionCartReorderItems(
            $cartReorderRequestTransfer,
            $cartReorderTransfer,
        );

        // Assert
        $this->assertCount(2, $cartReorderTransfer->getOrderItems());
        $this->assertCount(2, $cartReorderTransfer->getOrderOrFail()->getItems());
    }

    /**
     * @return void
     */
    public function testDoesNothingWhenItemsWithProductQuantityRestrictionNotRequestedInCartReorderRequestTransfer(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->createProductWithSpecificProductQuantity(2, 10, 1);

        $cartReorderTransfer = (new CartReorderBuilder([
            CartReorderTransfer::ORDER => [
                OrderTransfer::ITEMS => [
                    $this->createOrderItemTransfer(1, $productConcreteTransfer->getSkuOrFail())->toArray(),
                    [
                        ItemTransfer::ID_SALES_ORDER_ITEM => 2,
                        ItemTransfer::SKU => static::TEST_ITEM_SKU,
                    ],
                ],
            ],
            CartReorderTransfer::ORDER_ITEMS => [
                [
                    ItemTransfer::ID_SALES_ORDER_ITEM => 2,
                    ItemTransfer::SKU => static::TEST_ITEM_SKU,
                ],
            ],
        ]))->build();
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())->addIdSalesOrderItem(2);

        // Act
        $cartReorderTransfer = $this->tester->getFacade()->mergeProductQuantityRestrictionCartReorderItems(
            $cartReorderRequestTransfer,
            $cartReorderTransfer,
        );

        // Assert
        $this->assertCount(1, $cartReorderTransfer->getOrderItems());
        $this->assertCount(2, $cartReorderTransfer->getOrderOrFail()->getItems());

        $orderItemTransfer = $cartReorderTransfer->getOrderItems()->getIterator()->current();
        $this->assertSame(2, $orderItemTransfer->getIdSalesOrderItem());
    }

    /**
     * @dataProvider throwsNullValueExceptionWhenRequiredItemPropertyIsNotSetDataProvider
     *
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     * @param string $exceptionMessage
     *
     * @return void
     */
    public function testThrowsNullValueExceptionWhenRequiredItemPropertyIsNotSet(CartReorderTransfer $cartReorderTransfer, string $exceptionMessage): void
    {
        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage($exceptionMessage);

        // Act
        $this->tester->getFacade()->mergeProductQuantityRestrictionCartReorderItems(
            new CartReorderRequestTransfer(),
            $cartReorderTransfer,
        );
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\CartReorderTransfer|string>>
     */
    protected function throwsNullValueExceptionWhenRequiredItemPropertyIsNotSetDataProvider(): array
    {
        return [
            'order is not provided' => [
                (new CartReorderBuilder([
                    CartReorderTransfer::ORDER => null,
                ]))->withOrderItem($this->createOrderItemTransfer(1, static::TEST_ITEM_SKU)->toArray())
                    ->build(),
                sprintf('Property "order" of transfer `%s` is null.', CartReorderTransfer::class),
            ],
            'sku is not provided' => [
                (new CartReorderBuilder())
                    ->withOrder([
                        OrderTransfer::ITEMS => [
                            [
                                ItemTransfer::SKU => null,
                                ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                                ItemTransfer::QUANTITY => 1,
                            ],
                        ],
                    ])
                    ->withOrderItem($this->createOrderItemTransfer(1, static::TEST_ITEM_SKU)->toArray())
                    ->build(),
                sprintf('Property "sku" of transfer `%s` is null.', ItemTransfer::class),
            ],
        ];
    }

    /**
     * @param int $idSalesOrderItem
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createOrderItemTransfer(int $idSalesOrderItem, string $sku): ItemTransfer
    {
        return (new ItemBuilder([
            ItemTransfer::GROUP_KEY => static::TEST_ITEM_GROUP_KEY,
            ItemTransfer::QUANTITY => 1,
            ItemTransfer::ID_SALES_ORDER_ITEM => $idSalesOrderItem,
            ItemTransfer::SKU => $sku,
        ]))->build();
    }
}
