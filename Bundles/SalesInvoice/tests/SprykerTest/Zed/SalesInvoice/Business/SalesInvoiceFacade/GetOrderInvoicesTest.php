<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesInvoice\Business\SalesInvoiceFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\OrderInvoiceCriteriaTransfer;
use Spryker\Zed\Glossary\Communication\Plugin\TwigTranslatorPlugin;
use Spryker\Zed\SalesInvoice\SalesInvoiceDependencyProvider;
use SprykerTest\Zed\SalesInvoice\OrderInvoicesExpanderPluginMock;
use Twig\Environment;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesInvoice
 * @group Business
 * @group SalesInvoiceFacade
 * @group GetOrderInvoicesTest
 * Add your own group annotations below this line
 */
class GetOrderInvoicesTest extends Unit
{
    protected const INVOICE_NUMBER = 5;

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

        $this->tester->createInvoiceCollection(static::INVOICE_NUMBER);

        $this->tester->setDependency(SalesInvoiceDependencyProvider::TWIG_ENVIRONMENT, $this->getTwigEnvironmentMock());
    }

    /**
     * @return void
     */
    public function testGetOrderInvoicesReturnsUnprocessedInvoicesIfEmailSentCriteriaIsFalse(): void
    {
        // Arrange
        $idSalesOrder = $this->tester->createOrder()
            ->getIdSalesOrder();
        $invoiceWitEmailSent = $this->tester->haveOrderInvoice($idSalesOrder, [
            'email_sent' => true,
        ]);
        $orderInvoiceCriteriaTransfer = (new OrderInvoiceCriteriaTransfer())
            ->setIsEmailSent(false);

        // Act
        $orderInvoiceCollectionTransfer = $this->tester->getMockedFacade()
            ->getOrderInvoices($orderInvoiceCriteriaTransfer);

        // Assert
        $this->assertCount(static::INVOICE_NUMBER, $orderInvoiceCollectionTransfer->getOrderInvoices());
    }

    /**
     * @return void
     */
    public function testGetOrderInvoicesReturnsProcessedInvoicesIfEmailSentCriteriaIsTrue(): void
    {
        // Arrange
        $idSalesOrder = $this->tester->createOrder()
            ->getIdSalesOrder();
        $this->tester->haveOrderInvoice($idSalesOrder, [
            'email_sent' => true,
        ]);
        $orderInvoiceCriteriaTransfer = (new OrderInvoiceCriteriaTransfer())
            ->setIsEmailSent(true);

        // Act
        $orderInvoiceCollectionTransfer = $this->tester->getMockedFacade()
            ->getOrderInvoices($orderInvoiceCriteriaTransfer);

        // Assert
        $this->assertCount(1, $orderInvoiceCollectionTransfer->getOrderInvoices());
    }

    /**
     * @return void
     */
    public function testGetOrderInvoicesReturnsInvoicesWithCriteriaByOrderId(): void
    {
        // Arrange
        $idSalesOrder = $this->tester->createOrder()
            ->getIdSalesOrder();
        $this->tester->haveOrderInvoice($idSalesOrder);
        $orderInvoiceCriteriaTransfer = (new OrderInvoiceCriteriaTransfer())
            ->setSalesOrderIds([$idSalesOrder]);

        // Act
        $orderInvoiceCollectionTransfer = $this->tester->getMockedFacade()
            ->getOrderInvoices($orderInvoiceCriteriaTransfer);

        // Assert
        $this->assertCount(1, $orderInvoiceCollectionTransfer->getOrderInvoices());
        foreach ($orderInvoiceCollectionTransfer->getOrderInvoices() as $orderInvoiceTransfer) {
            $this->assertSame($idSalesOrder, $orderInvoiceTransfer->getIdSalesOrder());
        }
    }

    /**
     * @return void
     */
    public function testGetOrderInvoicesExpandsWithRenderedInvoiceTemplate(): void
    {
        // Arrange
        $orderInvoiceCriteriaTransfer = (new OrderInvoiceCriteriaTransfer())
            ->setExpandWithRenderedInvoice(true)
            ->setFilter(
                (new FilterTransfer())->setOffset(0)->setLimit(1)
            );

        // Act
        $orderInvoiceCollectionTransfer = $this->tester->getMockedFacade()
            ->getOrderInvoices($orderInvoiceCriteriaTransfer);

        // Assert
        foreach ($orderInvoiceCollectionTransfer->getOrderInvoices() as $orderInvoiceTransfer) {
            $this->assertNotNull($orderInvoiceTransfer->getRenderedInvoice());
        }
    }

    /**
     * @return void
     */
    public function testGetOrderInvoicesOrderInvoicesExpanderPluginExecuted(): void
    {
        // Arrange
        $this->tester->setDependency(SalesInvoiceDependencyProvider::PLUGINS_ORDER_INVOICES_EXPANDER, [
            new OrderInvoicesExpanderPluginMock(),
        ]);

        // Act
        $orderInvoiceCollectionTransfer = $this->tester->getMockedFacade()
            ->getOrderInvoices((new OrderInvoiceCriteriaTransfer()));

        // Assert
        foreach ($orderInvoiceCollectionTransfer->getOrderInvoices() as $orderInvoiceTransfer) {
            $this->assertSame(OrderInvoicesExpanderPluginMock::FAKE_REFERENCE_FOR_PLUGIN_CHECK, $orderInvoiceTransfer->getReference());
        }
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
            ->with(TwigTranslatorPlugin::class)
            ->willReturn(new TwigTranslatorPlugin());
        $twigEnvironmentMock->method('render')
            ->willReturn('Rendered page');

        return $twigEnvironmentMock;
    }
}
