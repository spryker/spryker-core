<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendment\Communication\Plugin\CartReorder;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\SalesOrderAmendment\Communication\Plugin\CartReorder\OriginalSalesOrderItemGroupKeyCartReorderItemHydratorPlugin;
use SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendment
 * @group Communication
 * @group Plugin
 * @group CartReorder
 * @group OriginalSalesOrderItemGroupKeyCartReorderItemHydratorPluginTest
 * Add your own group annotations below this line
 */
class OriginalSalesOrderItemGroupKeyCartReorderItemHydratorPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentCommunicationTester
     */
    protected SalesOrderAmendmentCommunicationTester $tester;

    /**
     * @return void
     */
    public function testShouldHydrateGroupKeysFromOrderItemsToReorderItems(): void
    {
        // Arrange
        $orderItems = [$this->buildItemTransfer(222), $this->buildItemTransfer(333)];
        $reorderItems = [
            (new ItemTransfer())->setIdSalesOrderItem($orderItems[0]->getIdSalesOrderItem()),
            (new ItemTransfer())->setIdSalesOrderItem($orderItems[1]->getIdSalesOrderItem()),
        ];

        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrderItems(new ArrayObject($orderItems))
            ->setReorderItems(new ArrayObject($reorderItems));

        // Act
        $cartReorderTransfer = (new OriginalSalesOrderItemGroupKeyCartReorderItemHydratorPlugin())
            ->hydrate($cartReorderTransfer);

        // Assert
        $this->assertSame(
            $orderItems[0]->getGroupKey(),
            $cartReorderTransfer->getReorderItems()->offsetGet(0)->getOriginalSalesOrderItemGroupKey(),
        );
        $this->assertSame(
            $orderItems[1]->getGroupKey(),
            $cartReorderTransfer->getReorderItems()->offsetGet(1)->getOriginalSalesOrderItemGroupKey(),
        );
    }

    /**
     * @return void
     */
    public function testShouldHydrateGroupKeysFromOrderItemsToNewReorderItems(): void
    {
        // Arrange
        $orderItems = [$this->buildItemTransfer(222), $this->buildItemTransfer(333)];
        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrderItems(new ArrayObject($orderItems));

        // Act
        $cartReorderTransfer = (new OriginalSalesOrderItemGroupKeyCartReorderItemHydratorPlugin())
            ->hydrate($cartReorderTransfer);

        // Assert
        $this->assertSame(
            $orderItems[0]->getGroupKey(),
            $cartReorderTransfer->getReorderItems()->offsetGet(0)->getOriginalSalesOrderItemGroupKey(),
        );
        $this->assertSame(
            $orderItems[1]->getGroupKey(),
            $cartReorderTransfer->getReorderItems()->offsetGet(1)->getOriginalSalesOrderItemGroupKey(),
        );
    }

    /**
     * @return void
     */
    public function testShouldThrowsRequiredTransferPropertyExceptionWhenOrderItemIdSalesOrderItemIsNotProvided(): void
    {
        // Arrange
        $orderItems = [$this->buildItemTransfer(222), $this->buildItemTransfer(333)];
        $reorderItems = [
            (new ItemTransfer())->setIdSalesOrderItem($orderItems[0]->getIdSalesOrderItem()),
            (new ItemTransfer())->setIdSalesOrderItem($orderItems[1]->getIdSalesOrderItem()),
        ];

        $orderItems[1]->setIdSalesOrderItem(null);

        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrderItems(new ArrayObject($orderItems))
            ->setReorderItems(new ArrayObject($reorderItems));

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage('Missing required property "idSalesOrderItem" for transfer Generated\Shared\Transfer\ItemTransfer.');

        // Act
        (new OriginalSalesOrderItemGroupKeyCartReorderItemHydratorPlugin())->hydrate($cartReorderTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowsRequiredTransferPropertyExceptionWhenOrderItemSkuIsNotProvided(): void
    {
        // Arrange
        $orderItems = [$this->buildItemTransfer(222), $this->buildItemTransfer(333)];
        $reorderItems = [
            (new ItemTransfer())->setIdSalesOrderItem($orderItems[0]->getIdSalesOrderItem()),
            (new ItemTransfer())->setIdSalesOrderItem($orderItems[1]->getIdSalesOrderItem()),
        ];

        $orderItems[1]->setSku(null);

        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrderItems(new ArrayObject($orderItems))
            ->setReorderItems(new ArrayObject($reorderItems));

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage('Missing required property "sku" for transfer Generated\Shared\Transfer\ItemTransfer.');

        // Act
        (new OriginalSalesOrderItemGroupKeyCartReorderItemHydratorPlugin())->hydrate($cartReorderTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowsRequiredTransferPropertyExceptionWhenOrderItemQuantityIsNotProvided(): void
    {
        // Arrange
        $orderItems = [$this->buildItemTransfer(222), $this->buildItemTransfer(333)];
        $reorderItems = [
            (new ItemTransfer())->setIdSalesOrderItem($orderItems[0]->getIdSalesOrderItem()),
            (new ItemTransfer())->setIdSalesOrderItem($orderItems[1]->getIdSalesOrderItem()),
        ];

        $orderItems[1]->setQuantity(null);

        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrderItems(new ArrayObject($orderItems))
            ->setReorderItems(new ArrayObject($reorderItems));

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage('Missing required property "quantity" for transfer Generated\Shared\Transfer\ItemTransfer.');

        // Act
        (new OriginalSalesOrderItemGroupKeyCartReorderItemHydratorPlugin())->hydrate($cartReorderTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowsRequiredTransferPropertyExceptionWhenOrderItemGroupKeyIsNotProvided(): void
    {
        // Arrange
        $orderItems = [$this->buildItemTransfer(222), $this->buildItemTransfer(333)];
        $reorderItems = [
            (new ItemTransfer())->setIdSalesOrderItem($orderItems[0]->getIdSalesOrderItem()),
            (new ItemTransfer())->setIdSalesOrderItem($orderItems[1]->getIdSalesOrderItem()),
        ];

        $orderItems[1]->setGroupKey(null);

        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrderItems(new ArrayObject($orderItems))
            ->setReorderItems(new ArrayObject($reorderItems));

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage('Missing required property "groupKey" for transfer Generated\Shared\Transfer\ItemTransfer.');

        // Act
        (new OriginalSalesOrderItemGroupKeyCartReorderItemHydratorPlugin())->hydrate($cartReorderTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowsRequiredTransferPropertyExceptionWhenReorderItemGroupKeyIsNotProvided(): void
    {
        // Arrange
        $orderItems = [$this->buildItemTransfer(222), $this->buildItemTransfer(333)];
        $reorderItems = [
            (new ItemTransfer())->setIdSalesOrderItem($orderItems[0]->getIdSalesOrderItem()),
            (new ItemTransfer())->setIdSalesOrderItem(null),
        ];

        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrderItems(new ArrayObject($orderItems))
            ->setReorderItems(new ArrayObject($reorderItems));

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage('Missing required property "idSalesOrderItem" for transfer Generated\Shared\Transfer\ItemTransfer.');

        // Act
        (new OriginalSalesOrderItemGroupKeyCartReorderItemHydratorPlugin())->hydrate($cartReorderTransfer);
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function buildItemTransfer(int $idSalesOrderItem): ItemTransfer
    {
        return (new ItemBuilder([
            ItemTransfer::ID_SALES_ORDER_ITEM => $idSalesOrderItem,
        ]))->build();
    }
}
