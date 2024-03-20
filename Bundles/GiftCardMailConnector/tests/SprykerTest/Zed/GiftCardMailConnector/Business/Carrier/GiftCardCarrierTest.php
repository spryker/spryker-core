<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GiftCardMailConnector\Business\Carrier;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\OrderFilterTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\GiftCardMailConnector\Business\Carrier\GiftCardCarrier;
use Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToCustomerFacadeInterface;
use Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToGiftCardFacadeInterface;
use Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToMailFacadeInterface;
use Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToSalesFacadeInterface;
use SprykerTest\Zed\GiftCardMailConnector\GiftCardMailConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group GiftCardMailConnector
 * @group Business
 * @group Carrier
 * @group GiftCardCarrierTest
 * Add your own group annotations below this line
 */
class GiftCardCarrierTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\GiftCardMailConnector\GiftCardMailConnectorBusinessTester
     */
    protected GiftCardMailConnectorBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([$this->tester::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testDeliverByIdSalesOrderItemExpandsMailTransferWithStoreName(): void
    {
        // Arrange
        $salesOrderTransfer = $this->tester->haveOrder([], $this->tester::DEFAULT_OMS_PROCESS_NAME);
        $orderTransfer = $this->tester->getLocator()->sales()->facade()
            ->getOrder(
                (new OrderFilterTransfer())
                    ->setSalesOrderId($salesOrderTransfer->getIdSalesOrder()),
            );

        // Assert
        $mailFacadeMock = $this->createMailFacadeMock();
        $mailFacadeMock->expects($this->once())
            ->method('handleMail')
            ->willReturnCallback(function (MailTransfer $mailTransfer) use ($orderTransfer) {
                $this->assertSame($orderTransfer->getStore(), $mailTransfer->getStoreName());
            });

        // Act
        $giftCardCarrier = new GiftCardCarrier(
            $mailFacadeMock,
            $this->createCustomerFacadeMock(),
            $this->createGiftCardFacadeMock(),
            $this->createSalesFacadeMock($orderTransfer),
        );
        $giftCardCarrier->deliverByIdSalesOrderItem($salesOrderTransfer->getIdSalesOrder());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToMailFacadeInterface
     */
    protected function createMailFacadeMock(): GiftCardMailConnectorToMailFacadeInterface
    {
        return $this->createMock(GiftCardMailConnectorToMailFacadeInterface::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToCustomerFacadeInterface
     */
    protected function createCustomerFacadeMock(): GiftCardMailConnectorToCustomerFacadeInterface
    {
        $customerFacadeMock = $this->createMock(GiftCardMailConnectorToCustomerFacadeInterface::class);
        $customerFacadeMock->expects($this->once())
            ->method('findByReference')
            ->willReturn($this->tester->haveCustomer());

        return $customerFacadeMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToGiftCardFacadeInterface
     */
    protected function createGiftCardFacadeMock(): GiftCardMailConnectorToGiftCardFacadeInterface
    {
        $giftCardFacadeMock = $this->createMock(GiftCardMailConnectorToGiftCardFacadeInterface::class);
        $giftCardFacadeMock->expects($this->once())
            ->method('findGiftCardByIdSalesOrderItem')
            ->willReturn($this->tester->haveGiftCard());

        return $giftCardFacadeMock;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToSalesFacadeInterface
     */
    protected function createSalesFacadeMock(OrderTransfer $orderTransfer): GiftCardMailConnectorToSalesFacadeInterface
    {
        $salesFacadeMock = $this->createMock(GiftCardMailConnectorToSalesFacadeInterface::class);
        $salesFacadeMock->expects($this->once())
            ->method('findOrderByIdSalesOrderItem')
            ->willReturn($orderTransfer);

        return $salesFacadeMock;
    }
}
