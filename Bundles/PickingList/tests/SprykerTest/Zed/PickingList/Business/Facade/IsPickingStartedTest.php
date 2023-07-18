<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PickingList\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PickingStartedRequestTransfer;
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
 * @group IsPickingStartedTest
 * Add your own group annotations below this line
 */
class IsPickingStartedTest extends Unit
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
    public function testShouldReturnTrueWhenExistStartedPickingListsForOrder(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $this->tester->createPickingListByOrder($orderTransfer);
        $pickingStartedRequestTransfer = (new PickingStartedRequestTransfer())->addOrder($orderTransfer);

        // Act
        $pickingStartedResponseTransfer = $this->tester->getFacade()->isPickingStarted($pickingStartedRequestTransfer);

        // Assert
        $this->assertCount(1, $pickingStartedResponseTransfer->getOrders());
        $this->assertTrue($pickingStartedResponseTransfer->getOrders()->getIterator()->current()->getIsPickingStartedOrFail());
    }

    /**
     * @return void
     */
    public function testShouldReturnFalseWhenPickingListNotExistsForOrder(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $pickingStartedRequestTransfer = (new PickingStartedRequestTransfer())->addOrder($orderTransfer);

        // Act
        $pickingStartedResponseTransfer = $this->tester->getFacade()->isPickingStarted($pickingStartedRequestTransfer);

        // Assert
        $this->assertCount(1, $pickingStartedResponseTransfer->getOrders());
        $this->assertFalse($pickingStartedResponseTransfer->getOrders()->getIterator()->current()->getIsPickingStartedOrFail());
    }

    /**
     * @return void
     */
    public function testShouldReturnFalseWhenPickingListHasReadyForPickingStatus(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $this->tester->createPickingListByOrder($orderTransfer, false);
        $pickingStartedRequestTransfer = (new PickingStartedRequestTransfer())->addOrder($orderTransfer);

        // Act
        $pickingStartedResponseTransfer = $this->tester->getFacade()->isPickingStarted($pickingStartedRequestTransfer);

        // Assert
        $this->assertCount(1, $pickingStartedResponseTransfer->getOrders());
        $this->assertFalse($pickingStartedResponseTransfer->getOrders()->getIterator()->current()->getIsPickingStartedOrFail());
    }

    /**
     * @return void
     */
    public function testShouldReturnTrueWhenPickingListHasPickingFinishedStatus(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $this->tester->createPickingListWithOnePickedAndOneNotPickedItems($orderTransfer);
        $pickingStartedRequestTransfer = (new PickingStartedRequestTransfer())->addOrder($orderTransfer);

        // Act
        $pickingStartedResponseTransfer = $this->tester->getFacade()->isPickingStarted($pickingStartedRequestTransfer);

        // Assert
        $this->assertCount(1, $pickingStartedResponseTransfer->getOrders());
        $this->assertTrue($pickingStartedResponseTransfer->getOrders()->getIterator()->current()->getIsPickingStartedOrFail());
    }

    /**
     * @return void
     */
    public function testShouldCheckAllOrders(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $this->tester->createPickingListByOrder($orderTransfer);

        $secondOrderTransfer = $this->tester->createPersistedOrderTransfer();

        $pickingStartedRequestTransfer = (new PickingStartedRequestTransfer())
            ->setOrders(new ArrayObject([$orderTransfer, $secondOrderTransfer]));

        // Act
        $pickingStartedResponseTransfer = $this->tester->getFacade()->isPickingStarted($pickingStartedRequestTransfer);

        // Assert
        $this->assertCount(2, $pickingStartedResponseTransfer->getOrders());
        $this->assertTrue($pickingStartedResponseTransfer->getOrders()->offsetGet(0)->getIsPickingStartedOrFail());
        $this->assertFalse($pickingStartedResponseTransfer->getOrders()->offsetGet(1)->getIsPickingStartedOrFail());
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenOrderDoesNotHaveIdSalesOrder(): void
    {
        // Arrange
        $pickingStartedRequestTransfer = (new PickingStartedRequestTransfer())->addOrder(new OrderTransfer());

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->isPickingStarted($pickingStartedRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenOrderItemDoesNotHaveUuid(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $orderTransfer->getItems()->getIterator()->current()->setUuid(null);
        $pickingStartedRequestTransfer = (new PickingStartedRequestTransfer())->addOrder($orderTransfer);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->isPickingStarted($pickingStartedRequestTransfer);
    }
}
