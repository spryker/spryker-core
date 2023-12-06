<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Sales\Business\SalesBusinessFactory;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Zed\Sales\SalesConfig;
use SprykerTest\Zed\Sales\Helper\BusinessHelper;
use SprykerTest\Zed\Sales\SalesBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Facade
 * @group SaveSalesOrderItemsTest
 * Add your own group annotations below this line
 */
class SaveSalesOrderItemsTest extends Unit
{
    /**
     * @var int
     */
    protected const TAX_RATE_DEFAULT = 10;

    /**
     * @var \SprykerTest\Zed\Sales\SalesBusinessTester
     */
    protected SalesBusinessTester $tester;

    /**
     * @var \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected SalesFacadeInterface $salesFacade;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);
        $this->salesFacade = $this->tester->getFacade();
        $this->mockSalesConfig();
    }

    /**
     * @return void
     */
    public function testReturnsItemsWithTaxRateAsFloat(): void
    {
        // Arrange
        $this->tester->createInitialState();
        $saveOrderTransfer = new SaveOrderTransfer();
        $quoteTransfer = $this->tester->getValidBaseQuoteTransfer();

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setTaxRate(static::TAX_RATE_DEFAULT);
        }

        $this->salesFacade->saveOrderRaw($quoteTransfer, $saveOrderTransfer);

        // Act
        $this->salesFacade->saveSalesOrderItems($quoteTransfer, $saveOrderTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->assertIsFloat($itemTransfer->getTaxRate());
        }
    }

    /**
     * @return void
     */
    public function testReturnsItemsWithoutTaxRate(): void
    {
        // Arrange
        $this->tester->createInitialState();
        $saveOrderTransfer = new SaveOrderTransfer();
        $quoteTransfer = $this->tester->getValidBaseQuoteTransfer();
        $this->salesFacade->saveOrderRaw($quoteTransfer, $saveOrderTransfer);

        // Act
        $this->salesFacade->saveSalesOrderItems($quoteTransfer, $saveOrderTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->assertNull($itemTransfer->getTaxRate());
        }
    }

    /**
     * @return void
     */
    protected function mockSalesConfig(): void
    {
        $businessFactory = new SalesBusinessFactory();

        $salesConfigMock = $this->getMockBuilder(SalesConfig::class)->setMethods(['determineProcessForOrderItem'])->getMock();
        $salesConfigMock->method('determineProcessForOrderItem')->willReturn(BusinessHelper::DEFAULT_OMS_PROCESS_NAME);

        $businessFactory->setConfig($salesConfigMock);
        $this->salesFacade->setFactory($businessFactory);
    }
}
