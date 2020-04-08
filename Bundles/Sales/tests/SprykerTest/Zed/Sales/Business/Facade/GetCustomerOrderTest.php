<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\OrderTransfer;
use Propel\Runtime\Exception\PropelException;
use Spryker\Zed\Sales\Business\Exception\InvalidSalesOrderException;
use Spryker\Zed\Sales\SalesDependencyProvider;
use Spryker\Zed\SalesExtension\Dependency\Plugin\CustomerOrderAccessCheckPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Facade
 * @group GetCustomerOrderTest
 * Add your own group annotations below this line
 */
class GetCustomerOrderTest extends Test
{
    protected const FAKE_CUSTOMER_ID = 6666;
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\Sales\SalesBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testGetCustomerOrder(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);
        $orderTransfer->setFkCustomer($orderTransfer->getCustomer()->getIdCustomer());

        // Act
        $storedOrderTransfer = $this->tester
            ->getFacade()
            ->getCustomerOrder($orderTransfer);

        // Assert
        $this->assertSame($orderTransfer->getIdSalesOrder(), $storedOrderTransfer->getIdSalesOrder());
    }

    /**
     * @return void
     */
    public function testGetCustomerOrderThrowsExceptionForOtherCustomer(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);

        $orderTransfer
            ->setFkCustomer($customerTransfer->getIdCustomer())
            ->setCustomer($customerTransfer);

        // Assert
        $this->expectException(InvalidSalesOrderException::class);

        // Act
        $this->tester
            ->getFacade()
            ->getCustomerOrder($orderTransfer);
    }

    /**
     * @return void
     */
    public function testGetCustomerOrderThrowsPropelExceptionForUndefinedCustomer(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);
        $orderTransfer->setFkCustomer(static::FAKE_CUSTOMER_ID);

        // Assert
        $this->expectException(PropelException::class);

        // Act
        $this->tester
            ->getFacade()
            ->getCustomerOrder($orderTransfer);
    }

    /**
     * @return void
     */
    public function testGetCustomerOrderSupportsCustomerOrderAccessCheckPluginStack(): void
    {
        // Arrange
        $this->tester->setDependency(
            SalesDependencyProvider::PLUGINS_CUSTOMER_ORDER_ACCESS_CHECK,
            [$this->getCustomerOrderAccessCheckPluginMock()]
        );

        $customerTransfer = $this->tester->haveCustomer();
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);

        // Act
        $storedOrderTransfer = $this->tester
            ->getFacade()
            ->getCustomerOrder((new OrderTransfer())
                ->setIdSalesOrder($orderTransfer->getIdSalesOrder())
                ->setFkCustomer($customerTransfer->getIdCustomer())
                ->setCustomer($customerTransfer));

        // Assert
        $this->assertSame($orderTransfer->getIdSalesOrder(), $storedOrderTransfer->getIdSalesOrder());
        $this->assertNotSame($customerTransfer->getCustomerReference(), $storedOrderTransfer->getCustomerReference());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesExtension\Dependency\Plugin\CustomerOrderAccessCheckPluginInterface
     */
    protected function getCustomerOrderAccessCheckPluginMock(): CustomerOrderAccessCheckPluginInterface
    {
        $customerOrderPreCheckPluginMock = $this
            ->getMockBuilder(CustomerOrderAccessCheckPluginInterface::class)
            ->getMock();

        $customerOrderPreCheckPluginMock
            ->method('check')
            ->willReturn(true);

        return $customerOrderPreCheckPluginMock;
    }
}
