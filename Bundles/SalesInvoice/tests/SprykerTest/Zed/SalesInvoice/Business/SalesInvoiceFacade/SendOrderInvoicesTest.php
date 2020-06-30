<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesInvoice\Business\SalesInvoiceFacade;

use Codeception\Test\Unit;
use Codeception\Util\Stub;
use Generated\Shared\Transfer\OrderInvoiceSendRequestTransfer;
use Spryker\Zed\Glossary\Communication\Plugin\TwigTranslatorPlugin;
use Spryker\Zed\SalesInvoice\Dependency\Facade\SalesInvoiceToMailFacadeInterface;
use Spryker\Zed\SalesInvoice\SalesInvoiceDependencyProvider;
use Twig\Environment;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesInvoice
 * @group Business
 * @group SalesInvoiceFacade
 * @group SendOrderInvoicesTest
 * Add your own group annotations below this line
 */
class SendOrderInvoicesTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesInvoice\SalesInvoiceBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->prepareTestStateMachine();

        $this->tester->setDependency(SalesInvoiceDependencyProvider::TWIG_ENVIRONMENT, $this->getTwigEnvironmentMock());
        $this->tester->setDependency(SalesInvoiceDependencyProvider::FACADE_MAIL, $this->getMailFacadeMock());
    }

    /**
     * @return void
     */
    public function testSendOrderInvoicesSendsEmailForUnprocessedInvoices(): void
    {
        // Arrange
        $this->tester->createInvoice();
        $orderInvoiceSendRequestTransfer = (new OrderInvoiceSendRequestTransfer())->setBatch(10);

        // Act
        $orderInvoiceSendResponseTransfer = $this->tester->getMockedFacade()
            ->sendOrderInvoices($orderInvoiceSendRequestTransfer);

        // Assert
        $this->assertEquals(1, $orderInvoiceSendResponseTransfer->getCount());
    }

    /**
     * @return void
     */
    public function testSendOrderInvoicesNotSendsEmailForProcessedInvoices(): void
    {
        // Arrange
        $idSalesOrder = $this->tester->createOrder()
            ->getIdSalesOrder();
        $this->tester->haveOrderInvoice($idSalesOrder, [
            'email_sent' => true,
        ]);
        $orderInvoiceSendRequestTransfer = (new OrderInvoiceSendRequestTransfer())->setBatch(10);

        // Act
        $orderInvoiceSendResponseTransfer = $this->tester->getMockedFacade()
            ->sendOrderInvoices($orderInvoiceSendRequestTransfer);

        // Assert
        $this->assertEquals(0, $orderInvoiceSendResponseTransfer->getCount());
    }

    /**
     * @return void
     */
    public function testSendOrderInvoicesSendsEmailForProcessedInvoicesWithForcedFlag(): void
    {
        // Arrange
        $idSalesOrder = $this->tester->createOrder()
            ->getIdSalesOrder();
        $this->tester->haveOrderInvoice($idSalesOrder, [
            'email_sent' => true,
        ]);
        $orderInvoiceSendRequestTransfer = (new OrderInvoiceSendRequestTransfer())
            ->setBatch(10)
            ->setForce(true);

        // Act
        $orderInvoiceSendResponseTransfer = $this->tester->getMockedFacade()
            ->sendOrderInvoices($orderInvoiceSendRequestTransfer);

        // Assert
        $this->assertEquals(1, $orderInvoiceSendResponseTransfer->getCount());
    }

    /**
     * @return void
     */
    public function testSendOrderInvoicesSendsEmailByOrderId(): void
    {
        // Arrange
        $this->tester->createInvoice();
        $idSalesOrder = $this->tester->createOrder()
            ->getIdSalesOrder();
        $this->tester->haveOrderInvoice($idSalesOrder);

        $orderInvoiceSendRequestTransfer = (new OrderInvoiceSendRequestTransfer())
            ->setBatch(10)
            ->addSalesOrderId($idSalesOrder);

        // Act
        $orderInvoiceSendResponseTransfer = $this->tester->getMockedFacade()
            ->sendOrderInvoices($orderInvoiceSendRequestTransfer);

        // Assert
        $this->assertEquals(1, $orderInvoiceSendResponseTransfer->getCount());
    }

    /**
     * @return \Spryker\Zed\SalesInvoice\Dependency\Facade\SalesInvoiceToMailFacadeInterface
     */
    protected function getMailFacadeMock(): SalesInvoiceToMailFacadeInterface
    {
        /** @var \Spryker\Zed\SalesInvoice\Dependency\Facade\SalesInvoiceToMailFacadeInterface $mailFacadeMock */
        $mailFacadeMock = Stub::makeEmpty(SalesInvoiceToMailFacadeInterface::class);

        return $mailFacadeMock;
    }

    /**
     * @return \Twig\Environment
     */
    protected function getTwigEnvironmentMock(): Environment
    {
        $twigEnvironmentMock = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();
        $twigEnvironmentMock->method('getExtension')
            ->with('translator')
            ->willReturn(new TwigTranslatorPlugin());
        $twigEnvironmentMock->method('render')
            ->willReturn('Rendered page');

        return $twigEnvironmentMock;
    }
}
