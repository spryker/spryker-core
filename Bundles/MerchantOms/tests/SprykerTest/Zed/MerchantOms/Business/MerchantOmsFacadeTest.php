<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantOms\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantOmsEventTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Zed\StateMachine\Business\Exception\StateMachineException;
use SprykerTest\Zed\MerchantOms\Mocks\TestStateMachineHandler;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantOms
 * @group Business
 * @group Facade
 * @group MerchantOmsFacadeTest
 *
 * Add your own group annotations below this line
 */
class MerchantOmsFacadeTest extends Unit
{
    protected const TEST_MERCHANT_ORDER_ITEM_ID = 1;
    protected const TEST_MERCHANT_OMS_PROCESS_NAME = 'Test01';
    protected const WRONG_MERCHANT_OMS_PROCESS_NAME = 'Test02';
    protected const EVENT_NAME_EXPORT = 'export';
    protected const EVENT_NAME_DELIVER = 'deliver';

    /**
     * @var \SprykerTest\Zed\MerchantOms\MerchantOmsBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testDispatchNewMerchantOrderEventReturnSuccess(): void
    {
        // Arrange
        $testStateMachineHandler = new TestStateMachineHandler();
        $merchantOmsFacade = $this->tester->createMerchantOmsFacade($testStateMachineHandler, static::TEST_MERCHANT_OMS_PROCESS_NAME);

        $merchantOrderItemTransfer = (new MerchantOrderItemTransfer())->setIdMerchantOrderItem(static::TEST_MERCHANT_ORDER_ITEM_ID);
        $merchantOrderTransfer = (new MerchantOrderTransfer())->addMerchantOrderItem($merchantOrderItemTransfer);

        // Act
        $merchantOrderResponseTransfer = $merchantOmsFacade->dispatchNewMerchantOrderEvent($merchantOrderTransfer);
        $stateMachineItemTransfer = $testStateMachineHandler->getStateMachineItemTransfer();

        // Assert
        $this->assertTrue($merchantOrderResponseTransfer->getIsSuccessful());
        $this->assertSame($stateMachineItemTransfer->getIdentifier(), $merchantOrderItemTransfer->getIdMerchantOrderItem());
    }

    /**
     * @return void
     */
    public function testDispatchNewMerchantOrderEventThrowExceptionWithWrongStateMachine(): void
    {
        // Assert
        $this->expectException(StateMachineException::class);

        // Arrange
        $merchantOmsFacade = $this->tester->createMerchantOmsFacade(new TestStateMachineHandler(), static::WRONG_MERCHANT_OMS_PROCESS_NAME);

        $merchantOrderItemTransfer = (new MerchantOrderItemTransfer())->setIdMerchantOrderItem(static::TEST_MERCHANT_ORDER_ITEM_ID);
        $merchantOrderTransfer = (new MerchantOrderTransfer())->addMerchantOrderItem($merchantOrderItemTransfer);

        // Act
        $merchantOmsFacade->dispatchNewMerchantOrderEvent($merchantOrderTransfer);
    }

    /**
     * @return void
     */
    public function testDispatchMerchantOrderItemEventReturnsSuccessWithCorrectEvent(): void
    {
        // Arrange
        $testStateMachineHandler = new TestStateMachineHandler();
        $merchantOmsFacade = $this->tester->createMerchantOmsFacade($testStateMachineHandler, static::TEST_MERCHANT_OMS_PROCESS_NAME);

        $merchantOrderItemTransfer = (new MerchantOrderItemTransfer())->setIdMerchantOrderItem(static::TEST_MERCHANT_ORDER_ITEM_ID);
        $merchantOrderTransfer = (new MerchantOrderTransfer())->addMerchantOrderItem($merchantOrderItemTransfer);
        $merchantOmsEventTransfer = (new MerchantOmsEventTransfer())->setEventName(static::EVENT_NAME_EXPORT);

        $merchantOmsFacade->dispatchNewMerchantOrderEvent($merchantOrderTransfer);
        $stateMachineItemTransfer = $testStateMachineHandler->getStateMachineItemTransfer();

        $merchantOrderItemTransfer->setFkStateMachineItemState($stateMachineItemTransfer->getIdItemState());

        // Act
        $merchantOrderItemResponseTransfer = $merchantOmsFacade->dispatchMerchantOrderItemEvent($merchantOrderItemTransfer, $merchantOmsEventTransfer);

        // Assert
        $this->assertTrue($merchantOrderItemResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testDispatchMerchantOrderItemEventReturnsFailWithInCorrectEvent(): void
    {
        // Arrange
        $testStateMachineHandler = new TestStateMachineHandler();
        $merchantOmsFacade = $this->tester->createMerchantOmsFacade($testStateMachineHandler, static::TEST_MERCHANT_OMS_PROCESS_NAME);

        $merchantOrderItemTransfer = (new MerchantOrderItemTransfer())->setIdMerchantOrderItem(static::TEST_MERCHANT_ORDER_ITEM_ID);
        $merchantOrderTransfer = (new MerchantOrderTransfer())->addMerchantOrderItem($merchantOrderItemTransfer);
        $merchantOmsEventTransfer = (new MerchantOmsEventTransfer())->setEventName(static::EVENT_NAME_DELIVER);

        $merchantOmsFacade->dispatchNewMerchantOrderEvent($merchantOrderTransfer);
        $stateMachineItemTransfer = $testStateMachineHandler->getStateMachineItemTransfer();

        $merchantOrderItemTransfer->setFkStateMachineItemState($stateMachineItemTransfer->getIdItemState());

        // Act
        $merchantOrderItemResponseTransfer = $merchantOmsFacade->dispatchMerchantOrderItemEvent($merchantOrderItemTransfer, $merchantOmsEventTransfer);

        // Assert
        $this->assertFalse($merchantOrderItemResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testDispatchMerchantOrderItemsEventReturnsSuccessWithCorrectEvent(): void
    {
        // Arrange
        $testStateMachineHandler = new TestStateMachineHandler();
        $merchantOmsFacade = $this->tester->createMerchantOmsFacade($testStateMachineHandler, static::TEST_MERCHANT_OMS_PROCESS_NAME);

        $merchantOrderItemTransfer = (new MerchantOrderItemTransfer())->setIdMerchantOrderItem(static::TEST_MERCHANT_ORDER_ITEM_ID);
        $merchantOrderTransfer = (new MerchantOrderTransfer())->addMerchantOrderItem($merchantOrderItemTransfer);
        $merchantOmsEventTransfer = (new MerchantOmsEventTransfer())->setEventName(static::EVENT_NAME_EXPORT);

        $merchantOmsFacade->dispatchNewMerchantOrderEvent($merchantOrderTransfer);
        $stateMachineItemTransfer = $testStateMachineHandler->getStateMachineItemTransfer();

        $merchantOrderItemTransfer->setFkStateMachineItemState($stateMachineItemTransfer->getIdItemState());

        $merchantOrderItemCollectionTransfer = new MerchantOrderItemCollectionTransfer();
        $merchantOrderItemCollectionTransfer->addMerchantOrderItem($merchantOrderItemTransfer);

        // Act
        $merchantOrderItemResponseTransfer = $merchantOmsFacade->dispatchMerchantOrderItemsEvent($merchantOrderItemCollectionTransfer, $merchantOmsEventTransfer);

        // Assert
        $this->assertTrue($merchantOrderItemResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testDispatchMerchantOrderItemsEventReturnsFailWithInCorrectEvent(): void
    {
        // Arrange
        $testStateMachineHandler = new TestStateMachineHandler();
        $merchantOmsFacade = $this->tester->createMerchantOmsFacade($testStateMachineHandler, static::TEST_MERCHANT_OMS_PROCESS_NAME);

        $merchantOrderItemTransfer = (new MerchantOrderItemTransfer())->setIdMerchantOrderItem(static::TEST_MERCHANT_ORDER_ITEM_ID);
        $merchantOrderTransfer = (new MerchantOrderTransfer())->addMerchantOrderItem($merchantOrderItemTransfer);
        $merchantOmsEventTransfer = (new MerchantOmsEventTransfer())->setEventName(static::EVENT_NAME_DELIVER);

        $merchantOmsFacade->dispatchNewMerchantOrderEvent($merchantOrderTransfer);
        $stateMachineItemTransfer = $testStateMachineHandler->getStateMachineItemTransfer();

        $merchantOrderItemTransfer->setFkStateMachineItemState($stateMachineItemTransfer->getIdItemState());

        $merchantOrderItemCollectionTransfer = new MerchantOrderItemCollectionTransfer();
        $merchantOrderItemCollectionTransfer->addMerchantOrderItem($merchantOrderItemTransfer);

        // Act
        $merchantOrderItemResponseTransfer = $merchantOmsFacade->dispatchMerchantOrderItemsEvent($merchantOrderItemCollectionTransfer, $merchantOmsEventTransfer);

        // Assert
        $this->assertFalse($merchantOrderItemResponseTransfer->getIsSuccessful());
    }
}
