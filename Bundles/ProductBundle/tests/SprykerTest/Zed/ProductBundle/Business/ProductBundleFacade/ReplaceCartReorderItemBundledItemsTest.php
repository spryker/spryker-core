<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\Cart;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CartReorderBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\ProductBundle\ProductBundleBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Business
 * @group Cart
 * @group ReplaceCartReorderItemBundledItemsTest
 * Add your own group annotations below this line
 */
class ReplaceCartReorderItemBundledItemsTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_PRODUCT_BUNDLE_IDENTIFIER = 'test-product-bundle-identifier';

    /**
     * @var \SprykerTest\Zed\ProductBundle\ProductBundleBusinessTester
     */
    protected ProductBundleBusinessTester $tester;

    /**
     * @return void
     */
    public function testReplacesBundledItemsWithProductBundleItem(): void
    {
        // Arrange
        $cartReorderTransfer = (new CartReorderBuilder([
            CartReorderTransfer::ORDER => [
                OrderTransfer::ITEMS => [
                    $this->createOrderItemTransfer(1)->toArray(),
                    $this->createOrderItemTransfer(2)->toArray(),
                ],
            ],
            CartReorderTransfer::ORDER_ITEMS => [
                $this->createOrderItemTransfer(1)->toArray(),
                $this->createOrderItemTransfer(2)->toArray(),
            ],
        ]))->build();

        // Act
        $cartReorderTransfer = $this->tester->getFacade()->replaceCartReorderItemBundledItems(
            $cartReorderTransfer,
        );

        // Assert
        $this->assertCount(1, $cartReorderTransfer->getOrderItems());
        $this->assertCount(2, $cartReorderTransfer->getOrderOrFail()->getItems());

        $orderItemTransfer = $cartReorderTransfer->getOrderItems()->getIterator()->current();
        $this->assertSame(static::TEST_PRODUCT_BUNDLE_IDENTIFIER, $orderItemTransfer->getBundleItemIdentifier());
        $this->assertSame(1, $orderItemTransfer->getQuantity());
    }

    /**
     * @return void
     */
    public function testDoesNothingWhenNoBundledItemsProvided(): void
    {
        // Arrange
        $cartReorderTransfer = (new CartReorderBuilder([
            CartReorderTransfer::ORDER => [
                OrderTransfer::ITEMS => [
                    [ItemTransfer::RELATED_BUNDLE_ITEM_IDENTIFIER => null],
                    [ItemTransfer::RELATED_BUNDLE_ITEM_IDENTIFIER => null],
                ],
            ],
            CartReorderTransfer::ORDER_ITEMS => [
                [ItemTransfer::RELATED_BUNDLE_ITEM_IDENTIFIER => null],
                [ItemTransfer::RELATED_BUNDLE_ITEM_IDENTIFIER => null],
            ],
        ]))->build();

        // Act
        $cartReorderTransfer = $this->tester->getFacade()->replaceCartReorderItemBundledItems(
            $cartReorderTransfer,
        );

        // Assert
        $this->assertCount(2, $cartReorderTransfer->getOrderItems());
        $this->assertCount(2, $cartReorderTransfer->getOrderOrFail()->getItems());
    }

    /**
     * @return void
     */
    public function testDoesNothingWhenBundledItemsNotProvidedInCartReorderOrderItems(): void
    {
        // Arrange
        $cartReorderTransfer = (new CartReorderBuilder([
            CartReorderTransfer::ORDER => [
                OrderTransfer::ITEMS => [
                    $this->createOrderItemTransfer(1)->toArray(),
                    $this->createOrderItemTransfer(2)->toArray(),
                    [
                        ItemTransfer::ID_SALES_ORDER_ITEM => 3,
                        ItemTransfer::RELATED_BUNDLE_ITEM_IDENTIFIER => null,
                    ],
                ],
            ],
            CartReorderTransfer::ORDER_ITEMS => [
                [
                    ItemTransfer::ID_SALES_ORDER_ITEM => 3,
                    ItemTransfer::RELATED_BUNDLE_ITEM_IDENTIFIER => null,
                ],
            ],
        ]))->build();

        // Act
        $cartReorderTransfer = $this->tester->getFacade()->replaceCartReorderItemBundledItems(
            $cartReorderTransfer,
        );

        // Assert
        $this->assertCount(1, $cartReorderTransfer->getOrderItems());
        $this->assertCount(3, $cartReorderTransfer->getOrderOrFail()->getItems());

        $orderItemTransfer = $cartReorderTransfer->getOrderItems()->getIterator()->current();
        $this->assertNull($orderItemTransfer->getBundleItemIdentifier());
        $this->assertSame(3, $orderItemTransfer->getIdSalesOrderItem());
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
        $this->tester->getFacade()->replaceCartReorderItemBundledItems($cartReorderTransfer);
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
                ]))->withOrderItem($this->createOrderItemTransfer(1)->toArray())
                    ->build(),
                sprintf('Property "order" of transfer `%s` is null.', CartReorderTransfer::class),
            ],
            'idSalesOrderItem is not provided for item in order' => [
                (new CartReorderBuilder())
                    ->withOrder([
                        OrderTransfer::ITEMS => [
                            [
                                ItemTransfer::ID_SALES_ORDER_ITEM => null,
                                ItemTransfer::RELATED_BUNDLE_ITEM_IDENTIFIER => static::TEST_PRODUCT_BUNDLE_IDENTIFIER,
                                ItemTransfer::PRODUCT_BUNDLE => [
                                    ItemTransfer::BUNDLE_ITEM_IDENTIFIER => static::TEST_PRODUCT_BUNDLE_IDENTIFIER,
                                ],
                            ],
                        ],
                    ])
                    ->withOrderItem($this->createOrderItemTransfer(1)->toArray())
                    ->build(),
                sprintf('Property "idSalesOrderItem" of transfer `%s` is null.', ItemTransfer::class),
            ],
            'idSalesOrderItem is not provided for item in order items' => [
                (new CartReorderBuilder())
                    ->withOrder([OrderTransfer::ITEMS => [$this->createOrderItemTransfer(1)->toArray()]])
                    ->withOrderItem([
                        ItemTransfer::ID_SALES_ORDER_ITEM => null,
                        ItemTransfer::RELATED_BUNDLE_ITEM_IDENTIFIER => static::TEST_PRODUCT_BUNDLE_IDENTIFIER,
                        ItemTransfer::PRODUCT_BUNDLE => [
                            ItemTransfer::BUNDLE_ITEM_IDENTIFIER => static::TEST_PRODUCT_BUNDLE_IDENTIFIER,
                        ],
                    ])
                    ->build(),
                sprintf('Property "idSalesOrderItem" of transfer `%s` is null.', ItemTransfer::class),
            ],
            'bundleItemIdentifier is not provided for order item' => [
                (new CartReorderBuilder())
                    ->withOrder([
                        OrderTransfer::ITEMS => [
                            [
                                ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                                ItemTransfer::RELATED_BUNDLE_ITEM_IDENTIFIER => static::TEST_PRODUCT_BUNDLE_IDENTIFIER,
                                ItemTransfer::PRODUCT_BUNDLE => [
                                    ItemTransfer::BUNDLE_ITEM_IDENTIFIER => null,
                                ],
                            ],
                        ],
                    ])
                    ->withOrderItem($this->createOrderItemTransfer(1)->toArray())
                    ->build(),
                sprintf('Property "bundleItemIdentifier" of transfer `%s` is null.', ItemTransfer::class),
            ],
        ];
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createOrderItemTransfer(int $idSalesOrderItem): ItemTransfer
    {
        return (new ItemBuilder([
            ItemTransfer::QUANTITY => 1,
            ItemTransfer::ID_SALES_ORDER_ITEM => $idSalesOrderItem,
            ItemTransfer::RELATED_BUNDLE_ITEM_IDENTIFIER => static::TEST_PRODUCT_BUNDLE_IDENTIFIER,
        ]))->withProductBundle([
            ItemTransfer::BUNDLE_ITEM_IDENTIFIER => static::TEST_PRODUCT_BUNDLE_IDENTIFIER,
        ])->build();
    }
}
