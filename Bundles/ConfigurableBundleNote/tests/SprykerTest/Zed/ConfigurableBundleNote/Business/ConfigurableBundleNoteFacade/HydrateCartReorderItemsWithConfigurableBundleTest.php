<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundleNote\Business\ConfigurableBundleNoteFacade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\ConfigurableBundleNote\ConfigurableBundleNoteBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ConfigurableBundleNote
 * @group Business
 * @group ConfigurableBundleNoteFacade
 * @group HydrateCartReorderItemsWithConfigurableBundleTest
 * Add your own group annotations below this line
 */
class HydrateCartReorderItemsWithConfigurableBundleTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_CONFIGURABLE_BUNDLE_NOTE = 'test configurable bundle note';

    /**
     * @var \SprykerTest\Zed\ConfigurableBundleNote\ConfigurableBundleNoteBusinessTester
     */
    protected ConfigurableBundleNoteBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldAddReorderItemsWithConfigurableBundleNoteWhenItemWasNotAddedToReorderItems(): void
    {
        // Arrange
        $orderItemTransfers = new ArrayObject([
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                ItemTransfer::SALES_ORDER_CONFIGURED_BUNDLE => [
                    SalesOrderConfiguredBundleTransfer::NOTE => null,
                ],
            ]))->build(),
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 2,
                ItemTransfer::SALES_ORDER_CONFIGURED_BUNDLE => [
                    SalesOrderConfiguredBundleTransfer::NOTE => static::TEST_CONFIGURABLE_BUNDLE_NOTE,
                ],
            ]))->build(),
        ]);
        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrderItems($orderItemTransfers);

        // Act
        $cartReorderTransfer = $this->tester->getFacade()->hydrateCartReorderItemsWithConfigurableBundle($cartReorderTransfer);

        // Assert
        $this->assertCount(1, $cartReorderTransfer->getReorderItems());

        $reorderItemTransfer = $cartReorderTransfer->getReorderItems()->offsetGet(1);
        $this->assertNotNull($reorderItemTransfer->getConfiguredBundle());
        $this->assertSame(static::TEST_CONFIGURABLE_BUNDLE_NOTE, $reorderItemTransfer->getConfiguredBundleOrFail()->getNote());
        $this->assertSame($orderItemTransfers[1]->getIdSalesOrderItemOrFail(), $reorderItemTransfer->getIdSalesOrderItem());
        $this->assertSame($orderItemTransfers[1]->getSkuOrFail(), $reorderItemTransfer->getSku());
        $this->assertSame($orderItemTransfers[1]->getQuantityOrFail(), $reorderItemTransfer->getQuantity());
    }

    /**
     * @return void
     */
    public function testShouldAddConfigurableBundleNoteToReorderItemWhenItemWasPreviouslyAddedToReorderItems(): void
    {
        // Arrange
        $orderItemTransfers = new ArrayObject([
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                ItemTransfer::SALES_ORDER_CONFIGURED_BUNDLE => [
                    SalesOrderConfiguredBundleTransfer::NOTE => null,
                ],
            ]))->build(),
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 2,
                ItemTransfer::SALES_ORDER_CONFIGURED_BUNDLE => [
                    SalesOrderConfiguredBundleTransfer::NOTE => static::TEST_CONFIGURABLE_BUNDLE_NOTE,
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
        $cartReorderTransfer = $this->tester->getFacade()->hydrateCartReorderItemsWithConfigurableBundle($cartReorderTransfer);

        // Assert
        $this->assertCount(1, $cartReorderTransfer->getReorderItems());

        $reorderItemTransfer = $cartReorderTransfer->getReorderItems()->offsetGet(0);
        $this->assertNotNull($reorderItemTransfer->getConfiguredBundle());
        $this->assertSame(static::TEST_CONFIGURABLE_BUNDLE_NOTE, $reorderItemTransfer->getConfiguredBundleOrFail()->getNote());
    }

    /**
     * @return void
     */
    public function testShouldDoNothingWhenNoItemsWithConfigurableBundleNoteProvided(): void
    {
        // Arrange
        $orderItemTransfers = new ArrayObject([
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                ItemTransfer::SALES_ORDER_CONFIGURED_BUNDLE => [
                    SalesOrderConfiguredBundleTransfer::NOTE => null,
                ],
            ]))->build(),
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 2,
                ItemTransfer::SALES_ORDER_CONFIGURED_BUNDLE => [
                    SalesOrderConfiguredBundleTransfer::NOTE => null,
                ],
            ]))->build(),
        ]);
        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrderItems($orderItemTransfers);

        // Act
        $cartReorderTransfer = $this->tester->getFacade()->hydrateCartReorderItemsWithConfigurableBundle($cartReorderTransfer);

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
        $this->tester->getFacade()->hydrateCartReorderItemsWithConfigurableBundle($cartReorderTransfer);
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
                    ItemTransfer::SALES_ORDER_CONFIGURED_BUNDLE => [
                        SalesOrderConfiguredBundleTransfer::NOTE => static::TEST_CONFIGURABLE_BUNDLE_NOTE,
                    ],
                ]))->build(),
                sprintf('Property "idSalesOrderItem" of transfer `%s` is null.', ItemTransfer::class),
            ],
            'sku is not provided' => [
                (new ItemBuilder([
                    ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                    ItemTransfer::SKU => null,
                    ItemTransfer::SALES_ORDER_CONFIGURED_BUNDLE => [
                        SalesOrderConfiguredBundleTransfer::NOTE => static::TEST_CONFIGURABLE_BUNDLE_NOTE,
                    ],
                ]))->build(),
                sprintf('Property "sku" of transfer `%s` is null.', ItemTransfer::class),
            ],
            'quantity is not provided' => [
                (new ItemBuilder([
                    ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                    ItemTransfer::QUANTITY => null,
                    ItemTransfer::SALES_ORDER_CONFIGURED_BUNDLE => [
                        SalesOrderConfiguredBundleTransfer::NOTE => static::TEST_CONFIGURABLE_BUNDLE_NOTE,
                    ],
                ]))->build(),
                sprintf('Property "quantity" of transfer `%s` is null.', ItemTransfer::class),
            ],
        ];
    }
}
