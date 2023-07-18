<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PickingList\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PickingListGenerationFinishedRequestTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\PickingList\PickingListBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PickingList
 * @group Business
 * @group Facade
 * @group IsPickingListGenerationFinishedTest
 * Add your own group annotations below this line
 */
class IsPickingListGenerationFinishedTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PickingList\PickingListBusinessTester
     */
    protected PickingListBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([PickingListBusinessTester::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testShouldReturnTrueWhenPickingListExistsForOrder(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $this->tester->createPickingListByOrder($orderTransfer, false);
        $pickingListGenerationFinishedRequestTransfer = (new PickingListGenerationFinishedRequestTransfer())
            ->addOrder($orderTransfer);

        // Act
        $pickingListGenerationFinishedResponseTransfer = $this->tester->getFacade()
            ->isPickingListGenerationFinished($pickingListGenerationFinishedRequestTransfer);

        // Assert
        $this->assertCount(1, $pickingListGenerationFinishedResponseTransfer->getOrders());
        $this->assertTrue($pickingListGenerationFinishedResponseTransfer->getOrders()->getIterator()->current()->getIsPickingListGenerationFinished());
    }

    /**
     * @return void
     */
    public function testShouldReturnFalseWhenPickingListNotExistsForOrder(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $pickingListGenerationFinishedRequestTransfer = (new PickingListGenerationFinishedRequestTransfer())
            ->addOrder($orderTransfer);

        // Act
        $pickingListGenerationFinishedResponseTransfer = $this->tester->getFacade()
            ->isPickingListGenerationFinished($pickingListGenerationFinishedRequestTransfer);

        // Assert
        $this->assertCount(1, $pickingListGenerationFinishedResponseTransfer->getOrders());
        $this->assertFalse($pickingListGenerationFinishedResponseTransfer->getOrders()->getIterator()->current()->getIsPickingListGenerationFinished());
    }

    /**
     * @return void
     */
    public function testShouldCheckAllProvidedOrders(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createQuoteTransferWithThreeItems();
        $orderTransfer = $this->tester->createPersistedOrderTransferFromQuote($quoteTransfer);
        $this->tester->createPickingListByOrder($orderTransfer, false);

        $pickingListGenerationFinishedRequestTransfer = (new PickingListGenerationFinishedRequestTransfer())
            ->addOrder($orderTransfer);

        // Act
        $pickingListGenerationFinishedResponseTransfer = $this->tester->getFacade()
            ->isPickingListGenerationFinished($pickingListGenerationFinishedRequestTransfer);

        // Assert
        $this->assertCount(1, $pickingListGenerationFinishedResponseTransfer->getOrders());
        $this->assertFalse($pickingListGenerationFinishedResponseTransfer->getOrders()->getIterator()->current()->getIsPickingListGenerationFinished());
    }

    /**
     * @return void
     */
    public function testShouldCheckAllOrders(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $this->tester->createPickingListByOrder($orderTransfer, false);

        $secondOrderTransfer = $this->tester->createPersistedOrderTransfer();

        $pickingListGenerationFinishedRequestTransfer = (new PickingListGenerationFinishedRequestTransfer())
            ->setOrders(new ArrayObject([$orderTransfer, $secondOrderTransfer]));

        // Act
        $pickingListGenerationFinishedResponseTransfer = $this->tester->getFacade()
            ->isPickingListGenerationFinished($pickingListGenerationFinishedRequestTransfer);

        // Assert
        $this->assertCount(2, $pickingListGenerationFinishedResponseTransfer->getOrders());
        $this->assertTrue($pickingListGenerationFinishedResponseTransfer->getOrders()->offsetGet(0)->getIsPickingListGenerationFinished());
        $this->assertFalse($pickingListGenerationFinishedResponseTransfer->getOrders()->offsetGet(1)->getIsPickingListGenerationFinished());
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenOrderDoesNotHaveIdSalesOrder(): void
    {
        // Arrange
        $pickingListGenerationFinishedRequestTransfer = (new PickingListGenerationFinishedRequestTransfer())
            ->addOrder(new OrderTransfer());

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->isPickingListGenerationFinished($pickingListGenerationFinishedRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenOrderItemDoesNotHaveUuid(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $orderTransfer->getItems()->getIterator()->current()->setUuid(null);

        $pickingListGenerationFinishedRequestTransfer = (new PickingListGenerationFinishedRequestTransfer())
            ->addOrder($orderTransfer);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->isPickingListGenerationFinished($pickingListGenerationFinishedRequestTransfer);
    }
}
