<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesConfigurableBundle\Business\SalesConfigurableBundleFacade\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\SalesConfigurableBundle\SalesConfigurableBundleBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesConfigurableBundle
 * @group Business
 * @group SalesConfigurableBundleFacade
 * @group Facade
 * @group HydrateCartReorderItemsWithConfigurableBundleTest
 * Add your own group annotations below this line
 */
class HydrateCartReorderItemsWithConfigurableBundleTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_CONFIGURABLE_BUNDLE_TEMPLATE_UUID = 'test-configurable-bundle-template-uuid';

    /**
     * @var string
     */
    protected const TEST_CONFIGURABLE_BUNDLE_NAME = 'test-configurable-bundle-name';

    /**
     * @var string
     */
    protected const TEST_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_UUID = 'test-configurable-bundle-template-slot-uuid';

    /**
     * @var \SprykerTest\Zed\SalesConfigurableBundle\SalesConfigurableBundleBusinessTester
     */
    protected SalesConfigurableBundleBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldAddReorderItemsWithConfigurableBundleDataWhenItemWasNotAddedToReorderItems(): void
    {
        // Arrange
        $orderItemTransfers = new ArrayObject([
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                ItemTransfer::SALES_ORDER_CONFIGURED_BUNDLE => null,
                ItemTransfer::SALES_ORDER_CONFIGURED_BUNDLE_ITEM => null,
            ]))->build(),
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 2,
            ]))->withSalesOrderConfiguredBundle([
                SalesOrderConfiguredBundleTransfer::ID_SALES_ORDER_CONFIGURED_BUNDLE => 1,
                SalesOrderConfiguredBundleTransfer::NAME => static::TEST_CONFIGURABLE_BUNDLE_NAME,
                SalesOrderConfiguredBundleTransfer::CONFIGURABLE_BUNDLE_TEMPLATE_UUID => static::TEST_CONFIGURABLE_BUNDLE_TEMPLATE_UUID,
            ])->withSalesOrderConfiguredBundleItem([
                SalesOrderConfiguredBundleItemTransfer::CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_UUID => static::TEST_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_UUID,
            ])->build(),
        ]);
        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrderItems($orderItemTransfers);

        // Act
        $cartReorderTransfer = $this->tester->getFacade()->hydrateCartReorderItemsWithConfigurableBundle($cartReorderTransfer);

        // Assert
        $this->assertCount(1, $cartReorderTransfer->getReorderItems());

        $reorderItemTransfer = $cartReorderTransfer->getReorderItems()->offsetGet(1);
        $this->assertNotNull($reorderItemTransfer->getConfiguredBundle());
        $this->assertNotNull($reorderItemTransfer->getConfiguredBundleOrFail()->getTemplate());
        $this->assertNotNull($reorderItemTransfer->getConfiguredBundleItem());
        $this->assertNotNull($reorderItemTransfer->getConfiguredBundleItemOrFail()->getSlot());
        $this->assertSame(
            static::TEST_CONFIGURABLE_BUNDLE_TEMPLATE_UUID,
            $reorderItemTransfer->getConfiguredBundleOrFail()->getTemplateOrFail()->getUuid(),
        );
        $this->assertSame(
            static::TEST_CONFIGURABLE_BUNDLE_NAME,
            $reorderItemTransfer->getConfiguredBundleOrFail()->getTemplateOrFail()->getName(),
        );
        $this->assertSame(
            static::TEST_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_UUID,
            $reorderItemTransfer->getConfiguredBundleItemOrFail()->getSlotOrFail()->getUuid(),
        );
        $this->assertSame($orderItemTransfers[1]->getIdSalesOrderItemOrFail(), $reorderItemTransfer->getIdSalesOrderItem());
        $this->assertSame($orderItemTransfers[1]->getSkuOrFail(), $reorderItemTransfer->getSku());
        $this->assertSame($orderItemTransfers[1]->getQuantityOrFail(), $reorderItemTransfer->getQuantity());
    }

    /**
     * @return void
     */
    public function testShouldAddConfigurableBundleDataToReorderItemWhenItemWasPreviouslyAddedToReorderItems(): void
    {
        // Arrange
        $orderItemTransfers = new ArrayObject([
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                ItemTransfer::SALES_ORDER_CONFIGURED_BUNDLE => null,
                ItemTransfer::SALES_ORDER_CONFIGURED_BUNDLE_ITEM => null,
            ]))->build(),
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 2,
            ]))->withSalesOrderConfiguredBundle([
                SalesOrderConfiguredBundleTransfer::ID_SALES_ORDER_CONFIGURED_BUNDLE => 1,
                SalesOrderConfiguredBundleTransfer::NAME => static::TEST_CONFIGURABLE_BUNDLE_NAME,
                SalesOrderConfiguredBundleTransfer::CONFIGURABLE_BUNDLE_TEMPLATE_UUID => static::TEST_CONFIGURABLE_BUNDLE_TEMPLATE_UUID,
            ])->withSalesOrderConfiguredBundleItem([
                SalesOrderConfiguredBundleItemTransfer::CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_UUID => static::TEST_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_UUID,
            ])->build(),
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
        $this->assertNotNull($reorderItemTransfer->getConfiguredBundleOrFail()->getTemplate());
        $this->assertNotNull($reorderItemTransfer->getConfiguredBundleItem());
        $this->assertNotNull($reorderItemTransfer->getConfiguredBundleItemOrFail()->getSlot());
        $this->assertSame(
            static::TEST_CONFIGURABLE_BUNDLE_TEMPLATE_UUID,
            $reorderItemTransfer->getConfiguredBundleOrFail()->getTemplateOrFail()->getUuid(),
        );
        $this->assertSame(
            static::TEST_CONFIGURABLE_BUNDLE_NAME,
            $reorderItemTransfer->getConfiguredBundleOrFail()->getTemplateOrFail()->getName(),
        );
        $this->assertSame(
            static::TEST_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_UUID,
            $reorderItemTransfer->getConfiguredBundleItemOrFail()->getSlotOrFail()->getUuid(),
        );
    }

    /**
     * @return void
     */
    public function testShouldDoNothingWhenNoItemsWithConfigurableBundleProvided(): void
    {
        // Arrange
        $orderItemTransfers = new ArrayObject([
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                ItemTransfer::SALES_ORDER_CONFIGURED_BUNDLE => null,
                ItemTransfer::SALES_ORDER_CONFIGURED_BUNDLE_ITEM => null,
            ]))->build(),
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 2,
                ItemTransfer::SALES_ORDER_CONFIGURED_BUNDLE => null,
                ItemTransfer::SALES_ORDER_CONFIGURED_BUNDLE_ITEM => null,
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
                        SalesOrderConfiguredBundleTransfer::ID_SALES_ORDER_CONFIGURED_BUNDLE => 1,
                        SalesOrderConfiguredBundleTransfer::QUANTITY => 1,
                        SalesOrderConfiguredBundleTransfer::CONFIGURABLE_BUNDLE_TEMPLATE_UUID => static::TEST_CONFIGURABLE_BUNDLE_TEMPLATE_UUID,
                    ],
                    ItemTransfer::SALES_ORDER_CONFIGURED_BUNDLE_ITEM => [],
                ]))->build(),
                sprintf('Property "idSalesOrderItem" of transfer `%s` is null.', ItemTransfer::class),
            ],
            'sku is not provided' => [
                (new ItemBuilder([
                    ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                    ItemTransfer::SKU => null,
                    ItemTransfer::SALES_ORDER_CONFIGURED_BUNDLE => [
                        SalesOrderConfiguredBundleTransfer::ID_SALES_ORDER_CONFIGURED_BUNDLE => 1,
                        SalesOrderConfiguredBundleTransfer::QUANTITY => 1,
                        SalesOrderConfiguredBundleTransfer::CONFIGURABLE_BUNDLE_TEMPLATE_UUID => static::TEST_CONFIGURABLE_BUNDLE_TEMPLATE_UUID,
                    ],
                    ItemTransfer::SALES_ORDER_CONFIGURED_BUNDLE_ITEM => [],
                ]))->build(),
                sprintf('Property "sku" of transfer `%s` is null.', ItemTransfer::class),
            ],
            'quantity is not provided' => [
                (new ItemBuilder([
                    ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                    ItemTransfer::QUANTITY => null,
                    ItemTransfer::SALES_ORDER_CONFIGURED_BUNDLE => [
                        SalesOrderConfiguredBundleTransfer::ID_SALES_ORDER_CONFIGURED_BUNDLE => 1,
                        SalesOrderConfiguredBundleTransfer::QUANTITY => 1,
                        SalesOrderConfiguredBundleTransfer::CONFIGURABLE_BUNDLE_TEMPLATE_UUID => static::TEST_CONFIGURABLE_BUNDLE_TEMPLATE_UUID,
                    ],
                    ItemTransfer::SALES_ORDER_CONFIGURED_BUNDLE_ITEM => [],
                ]))->build(),
                sprintf('Property "quantity" of transfer `%s` is null.', ItemTransfer::class),
            ],
        ];
    }
}
