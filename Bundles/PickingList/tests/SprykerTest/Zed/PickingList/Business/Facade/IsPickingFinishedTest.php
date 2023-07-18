<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PickingList\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PickingFinishedRequestTransfer;
use Generated\Shared\Transfer\PickingListItemTransfer;
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
 * @group IsPickingFinishedTest
 * Add your own group annotations below this line
 */
class IsPickingFinishedTest extends Unit
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
    public function testShouldReturnTrueWhenNotAllPickingListItemsWerePicked(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $this->tester->createPickingListWithOnePickedAndOneNotPickedItems($orderTransfer);
        $pickingFinishedRequestTransfer = (new PickingFinishedRequestTransfer())->addOrder($orderTransfer);

        // Act
        $pickingFinishedResponseTransfer = $this->tester->getFacade()->isPickingFinished($pickingFinishedRequestTransfer);

        // Assert
        $this->assertCount(1, $pickingFinishedResponseTransfer->getOrders());
        $this->assertTrue($pickingFinishedResponseTransfer->getOrders()->getIterator()->current()->getIsPickingFinishedOrFail());
    }

    /**
     * @return void
     */
    public function testShouldReturnFalseWhenQuantityLessThenNumberOfPickedAndNotPicked(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        /** @var \ArrayObject<\Generated\Shared\Transfer\ItemTransfer> $itemTransferCollection */
        $itemTransferCollection = $orderTransfer->getItems();

        $this->tester->createPickingListWithItems([
            $this->tester->createPickingListItemTransfer([
                PickingListItemTransfer::ORDER_ITEM => $itemTransferCollection->getIterator()->current(),
                PickingListItemTransfer::QUANTITY => 5,
                PickingListItemTransfer::NUMBER_OF_NOT_PICKED => 1,
                PickingListItemTransfer::NUMBER_OF_PICKED => 5,
            ]),
        ]);

        $pickingFinishedRequestTransfer = (new PickingFinishedRequestTransfer())->addOrder($orderTransfer);

        // Act
        $pickingFinishedResponseTransfer = $this->tester->getFacade()->isPickingFinished($pickingFinishedRequestTransfer);

        // Assert
        $this->assertCount(1, $pickingFinishedResponseTransfer->getOrders());
        $this->assertFalse($pickingFinishedResponseTransfer->getOrders()->getIterator()->current()->getIsPickingFinishedOrFail());
    }

    /**
     * @return void
     */
    public function testShouldReturnTrueWhenAllPickingListsFinishedForOrder(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        /** @var \ArrayObject<\Generated\Shared\Transfer\ItemTransfer> $itemTransferCollection */
        $itemTransferCollection = $orderTransfer->getItems();

        $this->tester->createPickingListWithItems([
            $this->tester->createPickingListItemTransfer([
                PickingListItemTransfer::ORDER_ITEM => $itemTransferCollection->getIterator()->current(),
                PickingListItemTransfer::QUANTITY => 5,
                PickingListItemTransfer::NUMBER_OF_NOT_PICKED => 0,
                PickingListItemTransfer::NUMBER_OF_PICKED => 5,
            ]),
            $this->tester->createPickingListItemTransfer([
                PickingListItemTransfer::ORDER_ITEM => $itemTransferCollection->getIterator()->current(),
                PickingListItemTransfer::QUANTITY => 3,
                PickingListItemTransfer::NUMBER_OF_PICKED => 3,
                PickingListItemTransfer::NUMBER_OF_NOT_PICKED => 0,
            ]),
        ]);
        $pickingFinishedRequestTransfer = (new PickingFinishedRequestTransfer())->addOrder($orderTransfer);

        // Act
        $pickingFinishedResponseTransfer = $this->tester->getFacade()->isPickingFinished($pickingFinishedRequestTransfer);

        // Assert
        $this->assertCount(1, $pickingFinishedResponseTransfer->getOrders());
        $this->assertTrue($pickingFinishedResponseTransfer->getOrders()->getIterator()->current()->getIsPickingFinishedOrFail());
    }

    /**
     * @return void
     */
    public function testShouldReturnFalseWhenPickingListNotExistsForOrder(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $pickingFinishedRequestTransfer = (new PickingFinishedRequestTransfer())->addOrder($orderTransfer);

        // Act
        $pickingFinishedResponseTransfer = $this->tester->getFacade()->isPickingFinished($pickingFinishedRequestTransfer);

        // Assert
        $this->assertCount(1, $pickingFinishedResponseTransfer->getOrders());
        $this->assertFalse($pickingFinishedResponseTransfer->getOrders()->getIterator()->current()->getIsPickingFinishedOrFail());
    }

    /**
     * @return void
     */
    public function testShouldReturnFalseWhenExistNotFinishedPickingListsForOrder(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $this->tester->createPickingListWithOnePickedAndOneNotPickedItems($orderTransfer);
        $this->tester->createPickingListByOrder($orderTransfer);
        $pickingFinishedRequestTransfer = (new PickingFinishedRequestTransfer())->addOrder($orderTransfer);

        // Act
        $pickingFinishedResponseTransfer = $this->tester->getFacade()->isPickingFinished($pickingFinishedRequestTransfer);

        // Assert
        $this->assertCount(1, $pickingFinishedResponseTransfer->getOrders());
        $this->assertFalse($pickingFinishedResponseTransfer->getOrders()->getIterator()->current()->getIsPickingFinishedOrFail());
    }

    /**
     * @return void
     */
    public function testShouldReturnFalseWhenPickingListHasReadyForPickingStatus(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $this->tester->createPickingListByOrder($orderTransfer, false);
        $pickingFinishedRequestTransfer = (new PickingFinishedRequestTransfer())->addOrder($orderTransfer);

        // Act
        $pickingFinishedResponseTransfer = $this->tester->getFacade()->isPickingFinished($pickingFinishedRequestTransfer);

        // Assert
        $this->assertCount(1, $pickingFinishedResponseTransfer->getOrders());
        $this->assertFalse($pickingFinishedResponseTransfer->getOrders()->getIterator()->current()->getIsPickingFinishedOrFail());
    }

    /**
     * @return void
     */
    public function testShouldReturnFalseWhenPickingListHasPickingStartedStatus(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $this->tester->createPickingListByOrder($orderTransfer);
        $pickingFinishedRequestTransfer = (new PickingFinishedRequestTransfer())->addOrder($orderTransfer);

        // Act
        $pickingFinishedResponseTransfer = $this->tester->getFacade()->isPickingFinished($pickingFinishedRequestTransfer);

        // Assert
        $this->assertCount(1, $pickingFinishedResponseTransfer->getOrders());
        $this->assertFalse($pickingFinishedResponseTransfer->getOrders()->getIterator()->current()->getIsPickingFinishedOrFail());
    }

    /**
     * @return void
     */
    public function testShouldCheckAllOrders(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $this->tester->createPickingListWithOnePickedAndOneNotPickedItems($orderTransfer);

        $secondOrderTransfer = $this->tester->createPersistedOrderTransfer();

        $pickingFinishedRequestTransfer = (new PickingFinishedRequestTransfer())
            ->setOrders(new ArrayObject([$orderTransfer, $secondOrderTransfer]));

        // Act
        $pickingFinishedResponseTransfer = $this->tester->getFacade()->isPickingFinished($pickingFinishedRequestTransfer);

        // Assert
        $this->assertCount(2, $pickingFinishedResponseTransfer->getOrders());
        $this->assertTrue($pickingFinishedResponseTransfer->getOrders()->offsetGet(0)->getIsPickingFinishedOrFail());
        $this->assertFalse($pickingFinishedResponseTransfer->getOrders()->offsetGet(1)->getIsPickingFinishedOrFail());
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenOrderDoesNotHaveIdSalesOrder(): void
    {
        // Arrange
        $pickingFinishedRequestTransfer = (new PickingFinishedRequestTransfer())->addOrder(new OrderTransfer());

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->isPickingFinished($pickingFinishedRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenOrderItemDoesNotHaveUuid(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $orderTransfer->getItems()->getIterator()->current()->setUuid(null);
        $pickingFinishedRequestTransfer = (new PickingFinishedRequestTransfer())->addOrder($orderTransfer);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->isPickingFinished($pickingFinishedRequestTransfer);
    }
}
