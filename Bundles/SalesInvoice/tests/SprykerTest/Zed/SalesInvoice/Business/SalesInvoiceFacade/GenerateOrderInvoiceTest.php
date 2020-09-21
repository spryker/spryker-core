<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesInvoice\Business\SalesInvoiceFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesInvoice\SalesInvoiceDependencyProvider;
use SprykerTest\Zed\SalesInvoice\OrderInvoiceBeforeSavePluginMock;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesInvoice
 * @group Business
 * @group SalesInvoiceFacade
 * @group GenerateOrderInvoiceTest
 * Add your own group annotations below this line
 */
class GenerateOrderInvoiceTest extends Unit
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
    }

    /**
     * @return void
     */
    public function testGenerateOrderGeneratesInvoiceForOrder(): void
    {
        // Arrange
        $idSalesOrder = $this->tester->createOrder()
            ->getIdSalesOrder();
        $orderTransfer = (new OrderTransfer())
            ->setIdSalesOrder($idSalesOrder);

        // Act
        $orderInvoiceResponseTransfer = $this->tester->getMockedFacade()->generateOrderInvoice($orderTransfer);

        // Assert
        $this->assertTrue($orderInvoiceResponseTransfer->getIsSuccessful());
        $this->assertNotNull($orderInvoiceResponseTransfer->getOrderInvoice()->getIdSalesOrderInvoice());
    }

    /**
     * @return void
     */
    public function testGenerateOrderSecondInvoiceIsNotGeneratedForOrder(): void
    {
        // Arrange
        $idSalesOrder = $this->tester->createOrder()
            ->getIdSalesOrder();
        $this->tester->haveOrderInvoice($idSalesOrder);
        $orderTransfer = (new OrderTransfer())
            ->setIdSalesOrder($idSalesOrder);

        // Act
        $orderInvoiceResponseTransfer = $this->tester->getMockedFacade()->generateOrderInvoice($orderTransfer);

        // Assert
        $this->assertFalse($orderInvoiceResponseTransfer->getIsSuccessful());
        $this->assertNull($orderInvoiceResponseTransfer->getOrderInvoice());
    }

    /**
     * @return void
     */
    public function testGenerateOrderOrderInvoiceBeforeSavePluginExecutedBeforeInvoiceCreated(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrder();
        $this->tester->setDependency(SalesInvoiceDependencyProvider::PLUGINS_ORDER_INVOICE_BEFORE_SAVE, [
            new OrderInvoiceBeforeSavePluginMock(),
        ]);

        // Act
        $orderInvoiceResponseTransfer = $this->tester->getMockedFacade()->generateOrderInvoice($orderTransfer);

        // Assert
        $this->assertSame(OrderInvoiceBeforeSavePluginMock::FAKE_REFERENCE_FOR_PLUGIN_CHECK, $orderInvoiceResponseTransfer->getOrderInvoice()->getReference());
    }
}
