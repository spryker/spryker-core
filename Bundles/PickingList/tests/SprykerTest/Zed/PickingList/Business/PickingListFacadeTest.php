<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PickingList\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PickingListCollectionRequestTransfer;
use Generated\Shared\Transfer\PickingListItemTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use SprykerTest\Zed\PickingList\PickingListBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PickingList
 * @group Business
 * @group Facade
 * @group PickingListFacadeTest
 * Add your own group annotations below this line
 */
class PickingListFacadeTest extends Unit
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
    public function testIsPickingListGenerationFinishedForOrderShouldReturnTrueWhenPickingListExistsForOrder(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $this->createPickingListWithoutUser($orderTransfer);

        // Act
        $status = $this->tester->getFacade()->isPickingListGenerationFinishedForOrder($orderTransfer);

        // Assert
        $this->assertTrue($status);
    }

    /**
     * @return void
     */
    public function testIsPickingListGenerationFinishedForOrderShouldReturnFalseWhenPickingListNotExistsForOrder(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();

        // Act
        $status = $this->tester->getFacade()->isPickingListGenerationFinishedForOrder($orderTransfer);

        // Assert
        $this->assertFalse($status);
    }

    /**
     * @return void
     */
    public function testIsPickingListGenerationFinishedForOrderShouldReturnFalseWhenPickingListsWereCreatedNotForAllOrderItems(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createQuoteTransferWithThreeItems();
        $orderTransfer = $this->tester->createPersistedOrderTransferFromQuote($quoteTransfer);
        $this->createPickingListWithoutUser($orderTransfer);

        // Act
        $status = $this->tester->getFacade()->isPickingListGenerationFinishedForOrder($orderTransfer);

        // Assert
        $this->assertFalse($status);
    }

    /**
     * @return void
     */
    public function testIsPickingStartedForOrderShouldReturnTrueWhenExistStartedPickingListsForOrder(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $this->createPickingListWithUser($orderTransfer);

        // Act
        $status = $this->tester->getFacade()->isPickingStartedForOrder($orderTransfer);

        // Assert
        $this->assertTrue($status);
    }

    /**
     * @return void
     */
    public function testIsPickingStartedForOrderShouldReturnFalseWhenPickingListNotExistsForOrder(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();

        // Act
        $status = $this->tester->getFacade()->isPickingStartedForOrder($orderTransfer);

        // Assert
        $this->assertFalse($status);
    }

    /**
     * @return void
     */
    public function testIsPickingStartedForOrderShouldReturnFalseWhenPickingListHasReadyForPickingStatus(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $this->createPickingListWithoutUser($orderTransfer);

        // Act
        $status = $this->tester->getFacade()->isPickingStartedForOrder($orderTransfer);

        // Assert
        $this->assertFalse($status);
    }

    /**
     * @return void
     */
    public function testIsPickingStartedForOrderShouldReturnTrueWhenPickingListHasPickingFinishedStatus(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $this->createPickingListWithOnePickedAndOneNotPickedItems($orderTransfer);

        // Act
        $status = $this->tester->getFacade()->isPickingStartedForOrder($orderTransfer);

        // Assert
        $this->assertTrue($status);
    }

    /**
     * @return void
     */
    public function testIsPickingFinishedForOrderShouldReturnFalseWhenNotAllPickingListsFinishedForOrder(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $this->createPickingListWithOnePickedAndOneNotPickedItems($orderTransfer);

        // Act
        $status = $this->tester->getFacade()->isPickingFinishedForOrder($orderTransfer);

        // Assert
        $this->assertFalse($status);
    }

    /**
     * @return void
     */
    public function testIsPickingFinishedForOrderShouldReturnFalseWhenQuantityLessThenNumberOfPickedAndNotPicked(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        /** @var \ArrayObject<\Generated\Shared\Transfer\ItemTransfer> $itemTransferCollection */
        $itemTransferCollection = $orderTransfer->getItems();

        $this->createPickingListWithItems([
            $this->tester->createPickingListItemTransfer([
                PickingListItemTransfer::ORDER_ITEM => $itemTransferCollection->getIterator()->current(),
                PickingListItemTransfer::QUANTITY => 5,
                PickingListItemTransfer::NUMBER_OF_NOT_PICKED => 1,
                PickingListItemTransfer::NUMBER_OF_PICKED => 5,
            ]),
        ]);

        // Act
        $status = $this->tester->getFacade()->isPickingFinishedForOrder($orderTransfer);

        // Assert
        $this->assertFalse($status);
    }

    /**
     * @return void
     */
    public function testIsPickingFinishedForOrderShouldReturnTrueWhenAllPickingListsFinishedForOrder(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        /** @var \ArrayObject<\Generated\Shared\Transfer\ItemTransfer> $itemTransferCollection */
        $itemTransferCollection = $orderTransfer->getItems();

        $this->createPickingListWithItems([
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

        // Act
        $status = $this->tester->getFacade()->isPickingFinishedForOrder($orderTransfer);

        // Assert
        $this->assertTrue($status);
    }

    /**
     * @return void
     */
    public function testIsPickingFinishedForOrderShouldReturnFalseWhenPickingListNotExistsForOrder(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();

        // Act
        $status = $this->tester->getFacade()->isPickingFinishedForOrder($orderTransfer);

        // Assert
        $this->assertFalse($status);
    }

    /**
     * @return void
     */
    public function testIsPickingFinishedForOrderShouldReturnFalseWhenExistNotFinishedPickingListsForOrder(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $this->createPickingListWithOnePickedAndOneNotPickedItems($orderTransfer);
        $this->createPickingListWithUser($orderTransfer);

        // Act
        $status = $this->tester->getFacade()->isPickingFinishedForOrder($orderTransfer);

        // Assert
        $this->assertFalse($status);
    }

    /**
     * @return void
     */
    public function testIsPickingFinishedForOrderShouldReturnFalseWhenPickingListHasReadyForPickingStatus(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $this->createPickingListWithoutUser($orderTransfer);

        // Act
        $status = $this->tester->getFacade()->isPickingFinishedForOrder($orderTransfer);

        // Assert
        $this->assertFalse($status);
    }

    /**
     * @return void
     */
    public function testIsPickingFinishedForOrderShouldReturnFalseWhenPickingListHasPickingStartedStatus(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $this->createPickingListWithUser($orderTransfer);

        // Act
        $status = $this->tester->getFacade()->isPickingFinishedForOrder($orderTransfer);

        // Assert
        $this->assertFalse($status);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function createPickingListWithoutUser(OrderTransfer $orderTransfer): void
    {
        /** @var \ArrayObject<\Generated\Shared\Transfer\ItemTransfer> $itemTransferCollection */
        $itemTransferCollection = $orderTransfer->getItems();

        $pickingListItemTransfer = $this->tester->createPickingListItemTransfer([
            PickingListItemTransfer::ORDER_ITEM => $itemTransferCollection->getIterator()->current(),
        ]);

        $pickingListTransfer = $this->tester->createPickingListTransfer([
            PickingListTransfer::USER => null,
            PickingListTransfer::WAREHOUSE => $this->tester->haveStock(),
            PickingListTransfer::PICKING_LIST_ITEMS => [
                $pickingListItemTransfer,
            ],
        ]);
        $this->tester->havePickingList($pickingListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function createPickingListWithUser(OrderTransfer $orderTransfer): void
    {
        /** @var \ArrayObject<\Generated\Shared\Transfer\ItemTransfer> $itemTransferCollection */
        $itemTransferCollection = $orderTransfer->getItems();

        $pickingListItemTransfer = $this->tester->createPickingListItemTransfer([
            PickingListItemTransfer::ORDER_ITEM => $itemTransferCollection->getIterator()->current(),
        ]);

        $pickingListTransfer = $this->tester->createPickingListTransfer([
            PickingListTransfer::USER => $this->tester->haveUser(),
            PickingListTransfer::WAREHOUSE => $this->tester->haveStock(),
            PickingListTransfer::PICKING_LIST_ITEMS => [
                $pickingListItemTransfer,
            ],
        ]);
        $this->tester->havePickingList($pickingListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function createPickingListWithOnePickedAndOneNotPickedItems(OrderTransfer $orderTransfer): void
    {
        /** @var \ArrayObject<\Generated\Shared\Transfer\ItemTransfer> $itemTransferCollection */
        $itemTransferCollection = $orderTransfer->getItems();

        $pickedPickingListItemTransfer = $this->tester->createPickingListItemTransfer([
            PickingListItemTransfer::ORDER_ITEM => $itemTransferCollection->getIterator()->current(),
            PickingListItemTransfer::QUANTITY => 5,
            PickingListItemTransfer::NUMBER_OF_PICKED => 5,
        ]);

        $notPickedPickingListItemTransfer = $this->tester->createPickingListItemTransfer([
            PickingListItemTransfer::ORDER_ITEM => $itemTransferCollection->getIterator()->current(),
            PickingListItemTransfer::QUANTITY => 3,
            PickingListItemTransfer::NUMBER_OF_NOT_PICKED => 3,
        ]);

        $this->createPickingListWithItems([$pickedPickingListItemTransfer, $notPickedPickingListItemTransfer]);
    }

    /**
     * @param list<\Generated\Shared\Transfer\PickingListItemTransfer> $pickingListItems
     *
     * @return void
     */
    protected function createPickingListWithItems(array $pickingListItems): void
    {
        $pickingListBusinessFactory = $this->tester->mockFactoryMethod(
            'getCreatePickingListValidatorCompositeRules',
            [],
        );

        $pickingListFacade = $this->tester->getFacade();
        $pickingListFacade->setFactory($pickingListBusinessFactory);

        $pickingListTransfer = $this->tester->createPickingListTransfer([
            PickingListTransfer::USER => $this->tester->haveUser(),
            PickingListTransfer::WAREHOUSE => $this->tester->haveStock(),
            PickingListTransfer::PICKING_LIST_ITEMS => $pickingListItems,
        ]);

        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(true);

        $pickingListFacade->createPickingListCollection($pickingListCollectionRequestTransfer);
    }
}
