<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderCancelRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Sales\SalesDependencyProvider;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Facade
 * @group CancelOrderTest
 * Add your own group annotations below this line
 */
class CancelOrderTest extends Test
{
    protected const DEFAULT_OMS_PROCESS_NAME_WITH_CANCELLABLE_FLAGS = 'Test05';
    protected const DEFAULT_OMS_PROCESS_NAME_WITHOUT_CANCELLABLE_FLAGS = 'Test01';

    protected const FAKE_ID_SALES_ORDER = 6666;
    protected const FAKE_CUSTOMER_REFERENCE = 'FAKE_CUSTOMER_REFERENCE';

    protected const CANCELLED_STATE_NAME = 'cancelled';

    /**
     * @see \Spryker\Zed\Sales\Business\Writer\OrderWriter::GLOSSARY_KEY_CUSTOMER_ORDER_NOT_FOUND
     */
    protected const GLOSSARY_KEY_CUSTOMER_ORDER_NOT_FOUND = 'sales.error.customer_order_not_found';

    /**
     * @see \Spryker\Zed\Sales\Business\Writer\OrderWriter::GLOSSARY_KEY_ORDER_CANNOT_BE_CANCELLED
     */
    protected const GLOSSARY_KEY_ORDER_CANNOT_BE_CANCELLED = 'sales.error.order_cannot_be_canceled_due_to_wrong_item_state';

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

        $this->tester->configureTestStateMachine([
            static::DEFAULT_OMS_PROCESS_NAME_WITH_CANCELLABLE_FLAGS,
            static::DEFAULT_OMS_PROCESS_NAME_WITHOUT_CANCELLABLE_FLAGS,
        ]);
    }

    /**
     * @return void
     */
    public function testCancelOrderWithCancellableOrderItems(): void
    {
        // Arrange
        $this->tester->setDependency(
            SalesDependencyProvider::HYDRATE_ORDER_PLUGINS,
            [$this->getOrderExpanderPluginMock()]
        );

        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME_WITH_CANCELLABLE_FLAGS);

        $orderCancelRequestTransfer = (new OrderCancelRequestTransfer())
            ->setIdSalesOrder($orderTransfer->getIdSalesOrder())
            ->setCustomer($orderTransfer->getCustomer());

        // Act
        $orderCancelResponseTransfer = $this->tester
            ->getFacade()
            ->cancelOrder($orderCancelRequestTransfer);

        // Assert
        $this->assertTrue($orderCancelResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::CANCELLED_STATE_NAME,
            $orderCancelResponseTransfer->getOrder()->getItems()->getIterator()->current()->getState()->getName()
        );
    }

    /**
     * @return void
     */
    public function testCancelOrderWithCancellableOrderItemsForAnotherCustomer(): void
    {
        // Arrange
        $this->tester->setDependency(
            SalesDependencyProvider::HYDRATE_ORDER_PLUGINS,
            [$this->getOrderExpanderPluginMock()]
        );

        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME_WITH_CANCELLABLE_FLAGS);

        $orderCancelRequestTransfer = (new OrderCancelRequestTransfer())
            ->setIdSalesOrder($orderTransfer->getIdSalesOrder())
            ->setCustomer($this->tester->haveCustomer());

        // Act
        $orderCancelResponseTransfer = $this->tester
            ->getFacade()
            ->cancelOrder($orderCancelRequestTransfer);

        // Assert
        $this->assertFalse($orderCancelResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_CUSTOMER_ORDER_NOT_FOUND,
            $orderCancelResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testCancelOrderWithFakeOrderReference(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME_WITH_CANCELLABLE_FLAGS);

        $orderCancelRequestTransfer = (new OrderCancelRequestTransfer())
            ->setIdSalesOrder(static::FAKE_ID_SALES_ORDER)
            ->setCustomer($orderTransfer->getCustomer());

        // Act
        $orderCancelResponseTransfer = $this->tester
            ->getFacade()
            ->cancelOrder($orderCancelRequestTransfer);

        // Assert
        $this->assertFalse($orderCancelResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_CUSTOMER_ORDER_NOT_FOUND,
            $orderCancelResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testCancelOrderWithFakeCustomerReference(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME_WITH_CANCELLABLE_FLAGS);

        $orderCancelRequestTransfer = (new OrderCancelRequestTransfer())
            ->setIdSalesOrder($orderTransfer->getIdSalesOrder())
            ->setCustomer((new CustomerTransfer())->setCustomerReference(static::FAKE_CUSTOMER_REFERENCE));

        // Act
        $orderCancelResponseTransfer = $this->tester
            ->getFacade()
            ->cancelOrder($orderCancelRequestTransfer);

        // Assert
        $this->assertFalse($orderCancelResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_CUSTOMER_ORDER_NOT_FOUND,
            $orderCancelResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testCancelOrderWithoutRequiredOrderReferenceField(): void
    {
        // Arrange
        $orderCancelRequestTransfer = (new OrderCancelRequestTransfer())
            ->setCustomer((new CustomerTransfer())->setCustomerReference(static::FAKE_CUSTOMER_REFERENCE));

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getFacade()
            ->cancelOrder($orderCancelRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCancelOrderWithNonCancellableOrderItems(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME_WITHOUT_CANCELLABLE_FLAGS);

        $orderCancelRequestTransfer = (new OrderCancelRequestTransfer())
            ->setIdSalesOrder($orderTransfer->getIdSalesOrder())
            ->setCustomer($orderTransfer->getCustomer());

        // Act
        $orderCancelResponseTransfer = $this->tester
            ->getFacade()
            ->cancelOrder($orderCancelRequestTransfer);

        // Assert
        $this->assertFalse($orderCancelResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_ORDER_CANNOT_BE_CANCELLED,
            $orderCancelResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPluginInterface
     */
    protected function getOrderExpanderPluginMock(): OrderExpanderPluginInterface
    {
        $orderExpanderPluginMock = $this
            ->getMockBuilder(OrderExpanderPluginInterface::class)
            ->getMock();

        $orderExpanderPluginMock
            ->method('hydrate')
            ->willReturnCallback(function (OrderTransfer $orderTransfer) {
                $orderTransfer->setIsCancellable(true);

                return $orderTransfer;
            });

        return $orderExpanderPluginMock;
    }
}
