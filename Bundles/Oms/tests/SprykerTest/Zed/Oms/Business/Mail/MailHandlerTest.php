<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\Mail;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Spryker\Zed\Oms\Business\Mail\MailHandler;
use Spryker\Zed\Oms\Dependency\Facade\OmsToMailInterface;
use Spryker\Zed\Oms\Dependency\Facade\OmsToSalesInterface;
use SprykerTest\Zed\Oms\OmsBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group Mail
 * @group MailHandlerTest
 * Add your own group annotations below this line
 */
class MailHandlerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Oms\OmsBusinessTester
     */
    protected OmsBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([$this->tester::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @dataProvider getMailTypes
     *
     * @param string $mailType
     *
     * @return void
     */
    public function testSendOrderMailExpandsMailTransferWithStore(string $mailType): void
    {
        // Arrange
        $orderTransfer = $this->tester->haveOrder([], $this->tester::DEFAULT_OMS_PROCESS_NAME);
        $orderTransfer = $this->tester->getOrderByIdSalesOrder($orderTransfer->getIdSalesOrder());
        $salesOrderEntity = (new SpySalesOrder())
            ->fromArray($orderTransfer->toArray())
            ->setBillingAddress((new SpySalesOrderAddress())->setAddress1($this->tester::FAKE_BILLING_ADDRESS));

        // Assert
        $mailFacadeMock = $this->createMailFacadeMock();
        $mailFacadeMock
            ->expects($this->once())
            ->method('handleMail')
            ->with($this->callback(function (MailTransfer $mailTransfer) use ($salesOrderEntity) {
                return $mailTransfer->getStoreName() === $salesOrderEntity->getStore();
            }));

        // Act
        $mailHandler = new MailHandler(
            $this->createSalesFacadeMock($orderTransfer),
            $mailFacadeMock,
            [],
        );
        $mailHandler->{$mailType}($salesOrderEntity);
    }

    /**
     * @return array
     */
    protected function getMailTypes(): array
    {
        return [
            ['sendOrderShippedMail'],
            ['sendOrderConfirmationMail'],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Dependency\Facade\OmsToSalesInterface
     */
    protected function createSalesFacadeMock(OrderTransfer $orderTransfer): OmsToSalesInterface
    {
        $salesFacadeMock = $this->createMock(OmsToSalesInterface::class);
        $salesFacadeMock->expects($this->once())
            ->method('getOrderByIdSalesOrder')
            ->willReturn($orderTransfer);

        return $salesFacadeMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Dependency\Facade\OmsToMailInterface
     */
    protected function createMailFacadeMock(): OmsToMailInterface
    {
        return $this->createMock(OmsToMailInterface::class);
    }
}
