<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GiftCardBalance\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GiftCardBalanceLogCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\GiftCardTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use SprykerTest\Zed\GiftCardBalance\GiftCardBalanceBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group GiftCardBalance
 * @group Business
 * @group Facade
 * @group DeleteGiftCardBalanceLogCollectionTest
 * Add your own group annotations below this line
 */
class DeleteGiftCardBalanceLogCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\GiftCardBalance\GiftCardBalanceBusinessTester
     */
    protected GiftCardBalanceBusinessTester $tester;

    /**
     * @return void
     */
    public function testDeletesFoundBySalesOrderIdsGiftCardBalanceLogs(): void
    {
        // Arrange
        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
        $saveOrderTransfer1 = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $saveOrderTransfer2 = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $giftCardTransfer = $this->tester->haveGiftCard();
        $this->tester->createGiftCardBalanceLogEntity($giftCardTransfer->getIdGiftCardOrFail(), $saveOrderTransfer1->getIdSalesOrderOrFail(), 100);
        $this->tester->createGiftCardBalanceLogEntity($giftCardTransfer->getIdGiftCardOrFail(), $saveOrderTransfer2->getIdSalesOrderOrFail(), 200);

        // Act
        $this->tester->getFacade()->deleteGiftCardBalanceLogCollection(
            (new GiftCardBalanceLogCollectionDeleteCriteriaTransfer())->addIdSalesOrder($saveOrderTransfer1->getIdSalesOrder()),
        );

        // Assert
        $this->assertGiftCardBalanceLogs($saveOrderTransfer1, $saveOrderTransfer2, $giftCardTransfer);
    }

    /**
     * @return void
     */
    public function testDoesNothingWhenGiftCardBalanceLogsAreNotFoundBySalesOrderIds(): void
    {
        // Arrange
        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
        $saveOrderTransfer1 = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $saveOrderTransfer2 = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $giftCardTransfer = $this->tester->haveGiftCard();
        $this->tester->createGiftCardBalanceLogEntity($giftCardTransfer->getIdGiftCardOrFail(), $saveOrderTransfer2->getIdSalesOrderOrFail(), 200);

        // Act
        $this->tester->getFacade()->deleteGiftCardBalanceLogCollection(
            (new GiftCardBalanceLogCollectionDeleteCriteriaTransfer())->addIdSalesOrder($saveOrderTransfer1->getIdSalesOrder()),
        );

        // Assert
        $this->assertGiftCardBalanceLogs($saveOrderTransfer1, $saveOrderTransfer2, $giftCardTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer1
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer2
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return void
     */
    protected function assertGiftCardBalanceLogs(
        SaveOrderTransfer $saveOrderTransfer1,
        SaveOrderTransfer $saveOrderTransfer2,
        GiftCardTransfer $giftCardTransfer
    ): void {
        $giftCardBalanceLogEntitiesIndexedByIdSalesOrder = $this->tester->getGiftCardBalanceLogEntitiesIndexedByIdSalesOrder([
            $saveOrderTransfer1->getIdSalesOrderOrFail(),
            $saveOrderTransfer2->getIdSalesOrderOrFail(),
        ]);

        $this->assertCount(1, $giftCardBalanceLogEntitiesIndexedByIdSalesOrder);
        $this->assertArrayHasKey($saveOrderTransfer2->getIdSalesOrderOrFail(), $giftCardBalanceLogEntitiesIndexedByIdSalesOrder);

        $giftCardBalanceLogEntity = $giftCardBalanceLogEntitiesIndexedByIdSalesOrder[$saveOrderTransfer2->getIdSalesOrderOrFail()];
        $this->assertSame($saveOrderTransfer2->getIdSalesOrderOrFail(), $giftCardBalanceLogEntity->getFkSalesOrder());
        $this->assertSame($giftCardTransfer->getIdGiftCardOrFail(), $giftCardBalanceLogEntity->getFkGiftCard());
        $this->assertSame(200, $giftCardBalanceLogEntity->getValue());
    }
}
