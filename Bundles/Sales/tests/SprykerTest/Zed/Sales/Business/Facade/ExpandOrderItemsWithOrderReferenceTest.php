<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

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
 * @group ExpandOrderItemsWithOrderReferenceTest
 * Add your own group annotations below this line
 */
class ExpandOrderItemsWithOrderReferenceTest extends Test
{
    protected const FAKE_ORDER_REFERENCE = 'FAKE_ORDER_REFERENCE';

    /**
     * @var \SprykerTest\Zed\Sales\SalesBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandOrderItemsWithOrderReferenceCopyOrderReferenceToItems(): void
    {
        // Arrange
        $orderTransfer = (new OrderTransfer())
            ->setOrderReference(static::FAKE_ORDER_REFERENCE)
            ->addItem((new ItemTransfer()))
            ->addItem((new ItemTransfer()));

        // Act
        $orderTransfer = $this->tester->getFacade()->expandOrderItemsWithOrderReference($orderTransfer);

        // Assert
        $this->assertSame($orderTransfer->getOrderReference(), $orderTransfer->getItems()->offsetGet(0)->getOrderReference());
        $this->assertSame($orderTransfer->getOrderReference(), $orderTransfer->getItems()->offsetGet(1)->getOrderReference());
    }

    /**
     * @return void
     */
    public function testExpandOrderItemsWithOrderReferenceThrowsExceptionWithEmptyOrderReference(): void
    {
        // Arrange
        $orderTransfer = (new OrderTransfer())
            ->setOrderReference(null)
            ->addItem((new ItemTransfer()))
            ->addItem((new ItemTransfer()));

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->expandOrderItemsWithOrderReference($orderTransfer);
    }
}
