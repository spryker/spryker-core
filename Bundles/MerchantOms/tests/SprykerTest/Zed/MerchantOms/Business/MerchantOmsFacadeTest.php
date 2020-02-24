<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantOms\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
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
    protected const EXISTING_MERCHANT_OMS_PROCESS_NAME = 'MerchantOmsProcessName';
    protected const NOT_EXISTING_MERCHANT_OMS_PROCESS_NAME = 'NotExistingMerchantOmsProcessName';
    protected const EVENT_NAME_EXPORT = 'export';
    protected const EVENT_NAME_DELIVER = 'deliver';

    /**
     * @var \SprykerTest\Zed\MerchantOms\MerchantOmsBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testTriggerForNewMerchantOrderItemsReturnSuccess(): void
    {
        // Arrange
        $testStateMachineHandler = new TestStateMachineHandler();
        $merchantOmsFacade = $this->tester->createMerchantOmsFacade($testStateMachineHandler, static::EXISTING_MERCHANT_OMS_PROCESS_NAME);
        $merchant = $this->tester->haveMerchant();

        $merchantOrderItemTransfer = (new MerchantOrderItemTransfer())
            ->setIdMerchantOrderItem(static::TEST_MERCHANT_ORDER_ITEM_ID);
        $merchantOmsTriggerRequestTransfer = (new MerchantOmsTriggerRequestTransfer())
            ->addMerchantOrderItem($merchantOrderItemTransfer)
            ->setMerchant($merchant);

        // Act
        $merchantOmsFacade->triggerForNewMerchantOrderItems($merchantOmsTriggerRequestTransfer);
        $stateMachineItemTransfer = $testStateMachineHandler->getStateMachineItemTransfer();

        // Assert
        $this->assertSame($stateMachineItemTransfer->getIdentifier(), $merchantOrderItemTransfer->getIdMerchantOrderItem());
    }

    /**
     * @return void
     */
    public function testTriggerForNewMerchantOrderItemsThrowsExceptionWithNotExistingStateMachine(): void
    {
        // Arrange
        $merchantOmsFacade = $this->tester->createMerchantOmsFacade(new TestStateMachineHandler(), static::NOT_EXISTING_MERCHANT_OMS_PROCESS_NAME);
        $merchant = $this->tester->haveMerchant();

        $merchantOrderItemTransfer = (new MerchantOrderItemTransfer())
            ->setIdMerchantOrderItem(static::TEST_MERCHANT_ORDER_ITEM_ID);
        $merchantOmsTriggerRequestTransfer = (new MerchantOmsTriggerRequestTransfer())
            ->addMerchantOrderItem($merchantOrderItemTransfer)
            ->setMerchant($merchant);

        // Assert
        $this->expectException(StateMachineException::class);

        // Act
        $merchantOmsFacade->triggerForNewMerchantOrderItems($merchantOmsTriggerRequestTransfer);
    }

    /**
     * @return void
     */
    public function testDispatchMerchantOrderItemsEventReturnsSuccessWithCorrectEvent(): void
    {
        // Arrange
        $testStateMachineHandler = new TestStateMachineHandler();
        $merchantOmsFacade = $this->tester->createMerchantOmsFacade($testStateMachineHandler, static::EXISTING_MERCHANT_OMS_PROCESS_NAME);
        $merchant = $this->tester->haveMerchant();

        $merchantOrderItemTransfer = (new MerchantOrderItemTransfer())
            ->setIdMerchantOrderItem(static::TEST_MERCHANT_ORDER_ITEM_ID);
        $merchantOmsTriggerRequestTransfer = (new MerchantOmsTriggerRequestTransfer())
            ->addMerchantOrderItem($merchantOrderItemTransfer)
            ->setMerchant($merchant);

        $merchantOmsFacade->triggerForNewMerchantOrderItems($merchantOmsTriggerRequestTransfer);

        $stateMachineItemTransfer = $testStateMachineHandler->getStateMachineItemTransfer();
        $merchantOrderItemTransfer->setFkStateMachineItemState($stateMachineItemTransfer->getIdItemState());

        $merchantOmsTriggerRequestTransfer->setMerchantOmsEventName(static::EVENT_NAME_EXPORT);

        // Act
        $merchantOmsFacade->triggerEventForMerchantOrderItems($merchantOmsTriggerRequestTransfer);
        $stateMachineItemTransfer = $testStateMachineHandler->getStateMachineItemTransfer();

        // Assert
        $this->assertSame($stateMachineItemTransfer->getIdentifier(), $merchantOrderItemTransfer->getIdMerchantOrderItem());
    }
}
