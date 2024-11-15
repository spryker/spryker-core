<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProduct\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\MerchantProduct\MerchantProductBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProduct
 * @group Business
 * @group Facade
 * @group HydrateCartReorderItemsWithMerchantReferenceTest
 * Add your own group annotations below this line
 */
class HydrateCartReorderItemsWithMerchantReferenceTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_MERCHANT_REFERENCE = 'test-merchant-reference';

    /**
     * @var \SprykerTest\Zed\MerchantProduct\MerchantProductBusinessTester
     */
    protected MerchantProductBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldAddReorderItemsWithMerchantReferenceWhenItemWasNotAddedToReorderItems(): void
    {
        // Arrange
        $orderItemTransfers = new ArrayObject([
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                ItemTransfer::MERCHANT_REFERENCE => null,
            ]))->build(),
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 2,
                ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
            ]))->build(),
        ]);
        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrderItems($orderItemTransfers);

        // Act
        $cartReorderTransfer = $this->tester->getFacade()->hydrateCartReorderItemsWithMerchantReference($cartReorderTransfer);

        // Assert
        $this->assertCount(1, $cartReorderTransfer->getReorderItems());

        $reorderItemTransfer = $cartReorderTransfer->getReorderItems()->offsetGet(1);
        $this->assertSame(static::TEST_MERCHANT_REFERENCE, $reorderItemTransfer->getMerchantReference());
        $this->assertSame($orderItemTransfers[1]->getIdSalesOrderItemOrFail(), $reorderItemTransfer->getIdSalesOrderItem());
        $this->assertSame($orderItemTransfers[1]->getSkuOrFail(), $reorderItemTransfer->getSku());
        $this->assertSame($orderItemTransfers[1]->getQuantityOrFail(), $reorderItemTransfer->getQuantity());
    }

    /**
     * @return void
     */
    public function testShouldAddMerchantReferenceToReorderItemWhenItemWasPreviouslyAddedToReorderItems(): void
    {
        // Arrange
        $orderItemTransfers = new ArrayObject([
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                ItemTransfer::MERCHANT_REFERENCE => null,
            ]))->build(),
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 2,
                ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
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
        $cartReorderTransfer = $this->tester->getFacade()->hydrateCartReorderItemsWithMerchantReference($cartReorderTransfer);

        // Assert
        $this->assertCount(1, $cartReorderTransfer->getReorderItems());

        $reorderItemTransfer = $cartReorderTransfer->getReorderItems()->offsetGet(0);
        $this->assertSame(static::TEST_MERCHANT_REFERENCE, $reorderItemTransfer->getMerchantReference());
    }

    /**
     * @return void
     */
    public function testShouldDoNothingWhenNoItemsWithMerchantReferenceProvided(): void
    {
        // Arrange
        $orderItemTransfers = new ArrayObject([
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                ItemTransfer::MERCHANT_REFERENCE => null,
            ]))->build(),
            (new ItemBuilder([
                ItemTransfer::ID_SALES_ORDER_ITEM => 2,
                ItemTransfer::MERCHANT_REFERENCE => null,
            ]))->build(),
        ]);
        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrderItems($orderItemTransfers);

        // Act
        $cartReorderTransfer = $this->tester->getFacade()->hydrateCartReorderItemsWithMerchantReference($cartReorderTransfer);

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
        $this->tester->getFacade()->hydrateCartReorderItemsWithMerchantReference($cartReorderTransfer);
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
                    ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
                ]))->build(),
                sprintf('Property "idSalesOrderItem" of transfer `%s` is null.', ItemTransfer::class),
            ],
            'sku is not provided' => [
                (new ItemBuilder([
                    ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                    ItemTransfer::SKU => null,
                    ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
                ]))->build(),
                sprintf('Property "sku" of transfer `%s` is null.', ItemTransfer::class),
            ],
            'quantity is not provided' => [
                (new ItemBuilder([
                    ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                    ItemTransfer::QUANTITY => null,
                    ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
                ]))->build(),
                sprintf('Property "quantity" of transfer `%s` is null.', ItemTransfer::class),
            ],
        ];
    }
}
