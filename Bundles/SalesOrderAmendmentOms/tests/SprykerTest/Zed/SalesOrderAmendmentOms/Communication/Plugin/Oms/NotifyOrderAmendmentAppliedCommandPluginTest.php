<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendmentOms\Communication\Plugin\Oms;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Mapper\OrderMapper;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Reader\SalesOrderAmendmentQuoteReader;
use Spryker\Zed\SalesOrderAmendmentOms\Business\SalesOrderAmendmentOmsBusinessFactory;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Sender\OrderAmendmentStatusMailNotificationSender;
use Spryker\Zed\SalesOrderAmendmentOms\Communication\Plugin\Oms\NotifyOrderAmendmentAppliedCommandPlugin;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToMailFacadeBridge;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToMailFacadeInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface;
use SprykerTest\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendmentOms
 * @group Communication
 * @group Plugin
 * @group Oms
 * @group NotifyOrderAmendmentAppliedCommandPluginTest
 * Add your own group annotations below this line
 */
class NotifyOrderAmendmentAppliedCommandPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const ORDER_REFERENCE = 'test-order-reference';

    /**
     * @uses \Spryker\Zed\SalesOrderAmendmentOms\Communication\Plugin\Mail\NotifyOrderAmendmentAppliedMailTypeBuilderPlugin::MAIL_TYPE
     *
     * @var string
     */
    protected const MAIL_TYPE_ORDER_AMENDMENT_APPLIED = 'notify order amendment applied';

    /**
     * @var \SprykerTest\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsCommunicationTester
     */
    protected SalesOrderAmendmentOmsCommunicationTester $tester;

    /**
     * @return void
     */
    public function testRunDoesNothingWhenSalesOrderAmendmentQuoteIsNotFound(): void
    {
        // Arrange
        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToMailFacadeInterface $mailFacadeMock */
        $mailFacadeMock = $this->createMailFacadeMock();
        $mailFacadeMock->expects($this->never())->method('handleMail');

        $salesOrderAmendmentFacadeMock = $this->createSalesOrderAmendmentFacadeMock(new SalesOrderAmendmentQuoteCollectionTransfer());

        $plugin = $this->createNotifyOrderAmendmentAppliedCommandPlugin($mailFacadeMock, $salesOrderAmendmentFacadeMock);

        // Act
        $plugin->run([], $this->createSalesOrderMock(), new ReadOnlyArrayObject());
    }

    /**
     * @return void
     */
    public function testRunSendsNotificationWhenSalesOrderAmendmentQuoteExists(): void
    {
        // Arrange
        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToMailFacadeInterface $mailFacadeMock */
        $mailFacadeMock = $this->createMailFacadeMock();
        $mailFacadeMock->expects($this->once())->method('handleMail')
            ->with($this->callback(function (MailTransfer $mailTransfer) {
                return $mailTransfer->getType() === static::MAIL_TYPE_ORDER_AMENDMENT_APPLIED
                    && $mailTransfer->getQuote() !== null;
            }));

        $salesOrderAmendmentQuoteCollectionTransfer = (new SalesOrderAmendmentQuoteCollectionTransfer())->addSalesOrderAmendmentQuote(
            (new SalesOrderAmendmentQuoteTransfer())->setQuote(
                (new QuoteTransfer())->setCustomer(new CustomerTransfer())->setStore(new StoreTransfer()),
            ),
        );
        $salesOrderAmendmentFacadeMock = $this->createSalesOrderAmendmentFacadeMock($salesOrderAmendmentQuoteCollectionTransfer);

        $plugin = $this->createNotifyOrderAmendmentAppliedCommandPlugin($mailFacadeMock, $salesOrderAmendmentFacadeMock);

        // Act
        $plugin->run([], $this->createSalesOrderMock(), new ReadOnlyArrayObject());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function createSalesOrderMock(): SpySalesOrder
    {
        $orderEntityMock = $this->createMock(SpySalesOrder::class);
        $orderEntityMock->method('getOrderReference')->willReturn(static::ORDER_REFERENCE);
        $orderEntityMock->method('getIdSalesOrder')->willReturn(1);

        return $orderEntityMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToMailFacadeInterface
     */
    protected function createMailFacadeMock(): SalesOrderAmendmentOmsToMailFacadeInterface
    {
        return $this->getMockBuilder(SalesOrderAmendmentOmsToMailFacadeBridge::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionTransfer $salesOrderAmendmentQuoteCollectionTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface
     */
    protected function createSalesOrderAmendmentFacadeMock(
        SalesOrderAmendmentQuoteCollectionTransfer $salesOrderAmendmentQuoteCollectionTransfer
    ): SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface {
        $salesOrderAmendmentFacadeMock = $this->createMock(SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface::class);
        $salesOrderAmendmentFacadeMock->method('getSalesOrderAmendmentQuoteCollection')->willReturn($salesOrderAmendmentQuoteCollectionTransfer);

        return $salesOrderAmendmentFacadeMock;
    }

    /**
     * @param \Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToMailFacadeInterface $mailFacadeMock
     * @param \Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface $salesOrderAmendmentFacadeMock
     *
     * @return \Spryker\Zed\SalesOrderAmendmentOms\Communication\Plugin\Oms\NotifyOrderAmendmentAppliedCommandPlugin
     */
    protected function createNotifyOrderAmendmentAppliedCommandPlugin(
        SalesOrderAmendmentOmsToMailFacadeInterface $mailFacadeMock,
        SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface $salesOrderAmendmentFacadeMock
    ): NotifyOrderAmendmentAppliedCommandPlugin {
        $plugin = new NotifyOrderAmendmentAppliedCommandPlugin();
        $plugin->setBusinessFactory($this->createBusinessFactory($mailFacadeMock, $salesOrderAmendmentFacadeMock));

        return $plugin;
    }

    /**
     * @param \Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToMailFacadeInterface $mailFacadeMock
     * @param \Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface $salesOrderAmendmentFacadeMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesOrderAmendmentOms\Business\SalesOrderAmendmentOmsBusinessFactory
     */
    protected function createBusinessFactory(
        SalesOrderAmendmentOmsToMailFacadeInterface $mailFacadeMock,
        SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface $salesOrderAmendmentFacadeMock
    ): SalesOrderAmendmentOmsBusinessFactory {
        $businessFactoryMock = $this->getMockBuilder(SalesOrderAmendmentOmsBusinessFactory::class)
            ->onlyMethods(['createOrderAmendmentStatusMailNotificationSender'])
            ->getMock();

        $businessFactoryMock->method('createOrderAmendmentStatusMailNotificationSender')->willReturnCallback(function () use ($mailFacadeMock, $salesOrderAmendmentFacadeMock) {
            return new OrderAmendmentStatusMailNotificationSender(
                $mailFacadeMock,
                new SalesOrderAmendmentQuoteReader($salesOrderAmendmentFacadeMock),
                $salesOrderAmendmentFacadeMock,
                new OrderMapper(),
            );
        });

        return $businessFactoryMock;
    }
}
