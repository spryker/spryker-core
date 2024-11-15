<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesProductConfiguration\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\SalesProductConfiguration\SalesProductConfigurationBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesProductConfiguration
 * @group Business
 * @group Facade
 * @group HydrateCartReorderItemsWithProductConfigurationTest
 * Add your own group annotations below this line
 */
class HydrateCartReorderItemsWithProductConfigurationTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_DISPLAY_DATA = 'test-display-data';

    /**
     * @var string
     */
    protected const TEST_CONFIGURATION = 'test-configuration';

    /**
     * @var string
     */
    protected const TEST_CONFIGURATOR_KEY = 'test-configurator-key';

    /**
     * @var \SprykerTest\Zed\SalesProductConfiguration\SalesProductConfigurationBusinessTester
     */
    protected SalesProductConfigurationBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldAddReorderItemsWithProductConfigurationInstanceWhenItemWasNotAddedToReorderItems(): void
    {
        // Arrange
        $orderItemTransfers = new ArrayObject([
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                ItemTransfer::SALES_ORDER_ITEM_CONFIGURATION => null,
            ]))->build(),
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 2,
                ItemTransfer::SALES_ORDER_ITEM_CONFIGURATION => [
                    SalesOrderItemConfigurationTransfer::DISPLAY_DATA => static::TEST_DISPLAY_DATA,
                    SalesOrderItemConfigurationTransfer::CONFIGURATION => static::TEST_CONFIGURATION,
                    SalesOrderItemConfigurationTransfer::CONFIGURATOR_KEY => static::TEST_CONFIGURATOR_KEY,
                ],
            ]))->build(),
        ]);
        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrderItems($orderItemTransfers);

        // Act
        $cartReorderTransfer = $this->tester->getFacade()->hydrateCartReorderItemsWithProductConfiguration($cartReorderTransfer);

        // Assert
        $this->assertCount(1, $cartReorderTransfer->getReorderItems());

        $reorderItemTransfer = $cartReorderTransfer->getReorderItems()->offsetGet(1);
        $this->assertNotNull($reorderItemTransfer->getProductConfigurationInstance());
        $this->assertSame(static::TEST_DISPLAY_DATA, $reorderItemTransfer->getProductConfigurationInstanceOrFail()->getDisplayData());
        $this->assertSame(static::TEST_CONFIGURATION, $reorderItemTransfer->getProductConfigurationInstanceOrFail()->getConfiguration());
        $this->assertSame(static::TEST_CONFIGURATOR_KEY, $reorderItemTransfer->getProductConfigurationInstanceOrFail()->getConfiguratorKey());
        $this->assertFalse($reorderItemTransfer->getProductConfigurationInstanceOrFail()->getIsComplete());
        $this->assertSame($orderItemTransfers[1]->getIdSalesOrderItemOrFail(), $reorderItemTransfer->getIdSalesOrderItem());
        $this->assertSame($orderItemTransfers[1]->getSkuOrFail(), $reorderItemTransfer->getSku());
        $this->assertSame($orderItemTransfers[1]->getQuantityOrFail(), $reorderItemTransfer->getQuantity());
    }

    /**
     * @return void
     */
    public function testShouldAddProductConfigurationInstanceToReorderItemWhenItemWasPreviouslyAddedToReorderItems(): void
    {
        // Arrange
        $orderItemTransfers = new ArrayObject([
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                ItemTransfer::SALES_ORDER_ITEM_CONFIGURATION => null,
            ]))->build(),
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 2,
                ItemTransfer::SALES_ORDER_ITEM_CONFIGURATION => [
                    SalesOrderItemConfigurationTransfer::DISPLAY_DATA => static::TEST_DISPLAY_DATA,
                    SalesOrderItemConfigurationTransfer::CONFIGURATION => static::TEST_CONFIGURATION,
                    SalesOrderItemConfigurationTransfer::CONFIGURATOR_KEY => static::TEST_CONFIGURATOR_KEY,
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
        $cartReorderTransfer = $this->tester->getFacade()->hydrateCartReorderItemsWithProductConfiguration($cartReorderTransfer);

        // Assert
        $this->assertCount(1, $cartReorderTransfer->getReorderItems());

        $reorderItemTransfer = $cartReorderTransfer->getReorderItems()->offsetGet(0);
        $this->assertNotNull($reorderItemTransfer->getProductConfigurationInstance());
        $this->assertSame(static::TEST_DISPLAY_DATA, $reorderItemTransfer->getProductConfigurationInstanceOrFail()->getDisplayData());
        $this->assertSame(static::TEST_CONFIGURATION, $reorderItemTransfer->getProductConfigurationInstanceOrFail()->getConfiguration());
        $this->assertSame(static::TEST_CONFIGURATOR_KEY, $reorderItemTransfer->getProductConfigurationInstanceOrFail()->getConfiguratorKey());
    }

    /**
     * @return void
     */
    public function testShouldDoNothingWhenNoItemsWithSalesProductItemConfigurationProvided(): void
    {
        // Arrange
        $orderItemTransfers = new ArrayObject([
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                ItemTransfer::SALES_ORDER_ITEM_CONFIGURATION => null,
            ]))->build(),
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 2,
                ItemTransfer::SALES_ORDER_ITEM_CONFIGURATION => null,
            ]))->build(),
        ]);
        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrderItems($orderItemTransfers);

        // Act
        $cartReorderTransfer = $this->tester->getFacade()->hydrateCartReorderItemsWithProductConfiguration($cartReorderTransfer);

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
        $this->tester->getFacade()->hydrateCartReorderItemsWithProductConfiguration($cartReorderTransfer);
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
                    ItemTransfer::SALES_ORDER_ITEM_CONFIGURATION => [
                        SalesOrderItemConfigurationTransfer::DISPLAY_DATA => static::TEST_DISPLAY_DATA,
                        SalesOrderItemConfigurationTransfer::CONFIGURATION => static::TEST_CONFIGURATION,
                        SalesOrderItemConfigurationTransfer::CONFIGURATOR_KEY => static::TEST_CONFIGURATOR_KEY,
                    ],
                ]))->build(),
                sprintf('Property "idSalesOrderItem" of transfer `%s` is null.', ItemTransfer::class),
            ],
            'sku is not provided' => [
                (new ItemBuilder([
                    ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                    ItemTransfer::SKU => null,
                    ItemTransfer::SALES_ORDER_ITEM_CONFIGURATION => [
                        SalesOrderItemConfigurationTransfer::DISPLAY_DATA => static::TEST_DISPLAY_DATA,
                        SalesOrderItemConfigurationTransfer::CONFIGURATION => static::TEST_CONFIGURATION,
                        SalesOrderItemConfigurationTransfer::CONFIGURATOR_KEY => static::TEST_CONFIGURATOR_KEY,
                    ],
                ]))->build(),
                sprintf('Property "sku" of transfer `%s` is null.', ItemTransfer::class),
            ],
            'quantity is not provided' => [
                (new ItemBuilder([
                    ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                    ItemTransfer::QUANTITY => null,
                    ItemTransfer::SALES_ORDER_ITEM_CONFIGURATION => [
                        SalesOrderItemConfigurationTransfer::DISPLAY_DATA => static::TEST_DISPLAY_DATA,
                        SalesOrderItemConfigurationTransfer::CONFIGURATION => static::TEST_CONFIGURATION,
                        SalesOrderItemConfigurationTransfer::CONFIGURATOR_KEY => static::TEST_CONFIGURATOR_KEY,
                    ],
                ]))->build(),
                sprintf('Property "quantity" of transfer `%s` is null.', ItemTransfer::class),
            ],
        ];
    }
}
