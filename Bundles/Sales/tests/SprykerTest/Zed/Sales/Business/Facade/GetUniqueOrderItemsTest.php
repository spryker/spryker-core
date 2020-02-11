<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

use ArrayObject;
use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Facade
 * @group GetUniqueOrderItemsTest
 * Add your own group annotations below this line
 */
class GetUniqueOrderItemsTest extends Test
{
    protected const FAKE_GROUP_KEY_1 = 'FAKE_GROUP_KEY_1';
    protected const FAKE_GROUP_KEY_2 = 'FAKE_GROUP_KEY_2';

    /**
     * @var \SprykerTest\Zed\Sales\SalesBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetUniqueItemsFromOrderReturnsUniqueItemsFromOrder(): void
    {
        // Arrange
        $orderTransfer = $this->createFakeOrder();

        // Act
        $itemTransfers = $this->tester->getFacade()->getUniqueItemsFromOrder($orderTransfer);

        // Assert
        $this->assertCount(2, $itemTransfers);
    }

    /**
     * @return void
     */
    public function testGetUniqueItemsFromOrderIncrementsQuantityAndSumPrice(): void
    {
        // Arrange
        $orderTransfer = $this->createFakeOrder();

        // Act
        $itemTransfers = $this->tester->getFacade()->getUniqueItemsFromOrder($orderTransfer);

        // Assert
        $this->assertSame(static::FAKE_GROUP_KEY_1, $itemTransfers[0]->getGroupKey());
        $this->assertSame(300, $itemTransfers[0]->getSumPrice());
        $this->assertSame(3, $itemTransfers[0]->getQuantity());
    }

    /**
     * @return void
     */
    public function testGetUniqueItemsFromOrderWithoutRequiredSkuField(): void
    {
        // Arrange
        $orderTransfer = $this->createFakeOrder();
        $orderTransfer->addItem(new ItemTransfer());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->getUniqueItemsFromOrder($orderTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createFakeOrder(): OrderTransfer
    {
        $itemTransfers = [
            (new ItemTransfer())
                ->setGroupKey(static::FAKE_GROUP_KEY_1)
                ->setQuantity(1)
                ->setSumPrice(100),
            (new ItemTransfer())
                ->setGroupKey(static::FAKE_GROUP_KEY_1)
                ->setQuantity(1)
                ->setSumPrice(100),
            (new ItemTransfer())
                ->setGroupKey(static::FAKE_GROUP_KEY_1)
                ->setQuantity(1)
                ->setSumPrice(100),
            (new ItemTransfer())
                ->setGroupKey(static::FAKE_GROUP_KEY_2)
                ->setQuantity(1)
                ->setSumPrice(100),
        ];

        return (new OrderTransfer())
            ->setItems(new ArrayObject($itemTransfers));
    }
}
