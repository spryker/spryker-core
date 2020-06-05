<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesReturn\Business\SalesReturnFacade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Sales\Communication\Plugin\Sales\CurrencyIsoCodeOrderItemExpanderPlugin;
use Spryker\Zed\Sales\SalesDependencyProvider;
use Spryker\Zed\SalesReturn\Communication\Plugin\Sales\UpdateOrderItemIsReturnableByItemStatePlugin;
use Spryker\Zed\SalesReturn\SalesReturnConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesReturn
 * @group Business
 * @group SalesReturnFacade
 * @group CreateReturnTest
 * Add your own group annotations below this line
 */
class CreateReturnTest extends Unit
{
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';
    protected const FAKE_RETURN_REASON = 'FAKE_RETURN_REASON';
    protected const FAKE_STORE_NAME = 'FAKE_STORE_NAME';

    protected const SHIPPED_STATE_NAME = 'shipped';
    protected const DELIVERED_STATE_NAME = 'delivered';

    protected const FAKE_STATE_NAME = 'FAKE_STATE_NAME';

    /**
     * @uses \Spryker\Zed\SalesReturn\Business\Writer\ReturnWriter::GLOSSARY_KEY_CREATE_RETURN_ITEM_REQUIRED_FIELDS_ERROR
     */
    protected const GLOSSARY_KEY_CREATE_RETURN_ITEM_REQUIRED_FIELDS_ERROR = 'return.create_return.validation.required_item_fields_error';

    /**
     * @uses \Spryker\Zed\SalesReturn\Business\Validator\ReturnValidator::GLOSSARY_KEY_CREATE_RETURN_ITEM_ERROR
     */
    protected const GLOSSARY_KEY_CREATE_RETURN_ITEM_ERROR = 'return.create_return.validation.items_error';

    /**
     * @uses \Spryker\Zed\SalesReturn\Business\Validator\ReturnValidator::GLOSSARY_KEY_CREATE_RETURN_ITEM_CURRENCY_ERROR
     */
    protected const GLOSSARY_KEY_CREATE_RETURN_ITEM_CURRENCY_ERROR = 'return.create_return.validation.items_currency_error';

    /**
     * @uses \Spryker\Zed\SalesReturn\Business\Writer\ReturnWriter::GLOSSARY_KEY_CREATE_RETURN_RETURNABLE_ITEM_ERROR
     */
    protected const GLOSSARY_KEY_CREATE_RETURN_RETURNABLE_ITEM_ERROR = 'return.create_return.validation.returnable_items_error';

    /**
     * @uses \Spryker\Zed\SalesReturn\Business\Validator\ReturnValidator::GLOSSARY_KEY_CREATE_RETURN_STORE_ERROR
     */
    protected const GLOSSARY_KEY_CREATE_RETURN_STORE_ERROR = 'return.create_return.validation.store_error';

    /**
     * @var \SprykerTest\Zed\SalesReturn\SalesReturnBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);

        $this->tester->setDependency(SalesDependencyProvider::PLUGINS_ORDER_ITEM_EXPANDER, [
            new UpdateOrderItemIsReturnableByItemStatePlugin(),
        ]);
    }

    /**
     * @return void
     */
    public function testCreateReturnCreatesReturn(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);
        $itemTransfer = $orderTransfer->getItems()->getIterator()->current();

        $returnItemTransfer = (new ReturnItemTransfer())
            ->setReason(static::FAKE_RETURN_REASON)
            ->setOrderItem((new ItemTransfer())->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItem()));

        $this->tester->setItemState($itemTransfer->getIdSalesOrderItem(), static::SHIPPED_STATE_NAME);

        $returnCreateRequestTransfer = (new ReturnCreateRequestTransfer())
            ->setCustomer($orderTransfer->getCustomer())
            ->setStore($orderTransfer->getStore())
            ->addReturnItem($returnItemTransfer);

        // Act
        $returnResponseTransfer = $this->tester
            ->getFacade()
            ->createReturn($returnCreateRequestTransfer);

        $returnTransfer = $returnResponseTransfer->getReturn();
        $returnItemTransfer = $returnTransfer->getReturnItems()->getIterator()->current();

        // Assert
        $this->assertTrue($returnResponseTransfer->getIsSuccessful());
        $this->assertSame($orderTransfer->getStore(), $returnTransfer->getStore());
        $this->assertSame(
            $orderTransfer->getCustomer()->getCustomerReference(),
            $returnTransfer->getCustomerReference()
        );

        $this->assertSame(static::FAKE_RETURN_REASON, $returnItemTransfer->getReason());
        $this->assertSame($itemTransfer->getIdSalesOrderItem(), $returnItemTransfer->getOrderItem()->getIdSalesOrderItem());
        $this->assertSame(
            sprintf(
                $this->getConfig()->getReturnReferenceFormat(),
                $orderTransfer->getCustomer()->getCustomerReference(),
                1
            ),
            $returnTransfer->getReturnReference()
        );
    }

    /**
     * @return void
     */
    public function testCreateReturnCreatesReturnWithEmptyReturnReason(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);
        $itemTransfer = $orderTransfer->getItems()->getIterator()->current();

        $returnItemTransfer = (new ReturnItemTransfer())
            ->setReason(null)
            ->setOrderItem((new ItemTransfer())->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItem()));

        $this->tester->setItemState($itemTransfer->getIdSalesOrderItem(), static::SHIPPED_STATE_NAME);

        $returnCreateRequestTransfer = (new ReturnCreateRequestTransfer())
            ->setCustomer($orderTransfer->getCustomer())
            ->setStore($orderTransfer->getStore())
            ->addReturnItem($returnItemTransfer);

        // Act
        $returnResponseTransfer = $this->tester
            ->getFacade()
            ->createReturn($returnCreateRequestTransfer);

        // Assert
        $this->assertTrue($returnResponseTransfer->getIsSuccessful());
        $this->assertNull($returnResponseTransfer->getReturn()->getReturnItems()->getIterator()->current()->getReason());
    }

    /**
     * @return void
     */
    public function testCreateReturnCreatesReturnFromSeveralOrdersFromOneCustomer(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        $firstOrderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);
        $secondOrderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);

        $firstItemTransfer = $firstOrderTransfer->getItems()->getIterator()->current();
        $secondItemTransfer = $secondOrderTransfer->getItems()->getIterator()->current();

        $this->tester->setItemState($firstItemTransfer->getIdSalesOrderItem(), static::SHIPPED_STATE_NAME);
        $this->tester->setItemState($secondItemTransfer->getIdSalesOrderItem(), static::SHIPPED_STATE_NAME);

        $firstReturnItemTransfer = (new ReturnItemTransfer())
            ->setOrderItem((new ItemTransfer())->setIdSalesOrderItem($firstItemTransfer->getIdSalesOrderItem()));

        $secondReturnItemTransfer = (new ReturnItemTransfer())
            ->setOrderItem((new ItemTransfer())->setIdSalesOrderItem($secondItemTransfer->getIdSalesOrderItem()));

        $returnCreateRequestTransfer = (new ReturnCreateRequestTransfer())
            ->setCustomer($customerTransfer)
            ->setStore($firstOrderTransfer->getStore())
            ->addReturnItem($firstReturnItemTransfer)
            ->addReturnItem($secondReturnItemTransfer);

        // Act
        $returnResponseTransfer = $this->tester
            ->getFacade()
            ->createReturn($returnCreateRequestTransfer);

        $returnTransfer = $returnResponseTransfer->getReturn();

        // Assert
        $this->assertTrue($returnResponseTransfer->getIsSuccessful());
        $this->assertCount(2, $returnTransfer->getReturnItems());
    }

    /**
     * @return void
     */
    public function testCreateReturnCreatesReturnFromSeveralOrdersFromDifferentCustomers(): void
    {
        // Arrange
        $firstOrderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);
        $secondOrderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);

        $firstItemTransfer = $firstOrderTransfer->getItems()->getIterator()->current();
        $secondItemTransfer = $secondOrderTransfer->getItems()->getIterator()->current();

        $this->tester->setItemState($firstItemTransfer->getIdSalesOrderItem(), static::SHIPPED_STATE_NAME);
        $this->tester->setItemState($secondItemTransfer->getIdSalesOrderItem(), static::SHIPPED_STATE_NAME);

        $firstReturnItemTransfer = (new ReturnItemTransfer())
            ->setOrderItem((new ItemTransfer())->setIdSalesOrderItem($firstItemTransfer->getIdSalesOrderItem()));

        $secondReturnItemTransfer = (new ReturnItemTransfer())
            ->setOrderItem((new ItemTransfer())->setIdSalesOrderItem($secondItemTransfer->getIdSalesOrderItem()));

        $returnCreateRequestTransfer = (new ReturnCreateRequestTransfer())
            ->setCustomer($firstOrderTransfer->getCustomer())
            ->setStore($firstOrderTransfer->getStore())
            ->addReturnItem($firstReturnItemTransfer)
            ->addReturnItem($secondReturnItemTransfer);

        // Act
        $returnResponseTransfer = $this->tester
            ->getFacade()
            ->createReturn($returnCreateRequestTransfer);

        // Assert
        $this->assertFalse($returnResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_CREATE_RETURN_ITEM_ERROR,
            $returnResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testCreateReturnCreatesReturnFromSeveralOrdersWithDifferentCurrencies(): void
    {
        // Arrange
        $this->tester->setDependency(SalesDependencyProvider::PLUGINS_ORDER_ITEM_EXPANDER, [
            new CurrencyIsoCodeOrderItemExpanderPlugin(),
        ]);

        $customerTransfer = $this->tester->haveCustomer();
        $currencyData = ['code' => 'CHF', 'name' => 'CHF', 'symbol' => 'CHF'];

        $firstOrderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);
        $secondOrderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer, $currencyData);

        $firstItemTransfer = $firstOrderTransfer->getItems()->getIterator()->current();
        $secondItemTransfer = $secondOrderTransfer->getItems()->getIterator()->current();

        $this->tester->setItemState($firstItemTransfer->getIdSalesOrderItem(), static::SHIPPED_STATE_NAME);
        $this->tester->setItemState($secondItemTransfer->getIdSalesOrderItem(), static::SHIPPED_STATE_NAME);

        $firstReturnItemTransfer = (new ReturnItemTransfer())
            ->setOrderItem((new ItemTransfer())->setIdSalesOrderItem($firstItemTransfer->getIdSalesOrderItem()));

        $secondReturnItemTransfer = (new ReturnItemTransfer())
            ->setOrderItem((new ItemTransfer())->setIdSalesOrderItem($secondItemTransfer->getIdSalesOrderItem()));

        $returnCreateRequestTransfer = (new ReturnCreateRequestTransfer())
            ->setCustomer($firstOrderTransfer->getCustomer())
            ->setStore($firstOrderTransfer->getStore())
            ->addReturnItem($firstReturnItemTransfer)
            ->addReturnItem($secondReturnItemTransfer);

        // Act
        $returnResponseTransfer = $this->tester
            ->getFacade()
            ->createReturn($returnCreateRequestTransfer);

        // Assert
        $this->assertFalse($returnResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_CREATE_RETURN_ITEM_CURRENCY_ERROR,
            $returnResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testCreateReturnCreatesReturnFromGuestOrder(): void
    {
        // Arrange
        $customerTransfer = (new CustomerBuilder())->build();

        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);
        $itemTransfer = $orderTransfer->getItems()->getIterator()->current();
        $this->tester->setItemState($itemTransfer->getIdSalesOrderItem(), static::SHIPPED_STATE_NAME);

        $returnItemTransfer = (new ReturnItemTransfer())
            ->setOrderItem((new ItemTransfer())->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItem()));

        $returnCreateRequestTransfer = (new ReturnCreateRequestTransfer())
            ->setCustomer(null)
            ->setStore($orderTransfer->getStore())
            ->addReturnItem($returnItemTransfer);

        // Act
        $returnResponseTransfer = $this->tester
            ->getFacade()
            ->createReturn($returnCreateRequestTransfer);

        // Assert
        $this->assertTrue($returnResponseTransfer->getIsSuccessful());
        $this->assertNull($returnResponseTransfer->getReturn()->getCustomerReference());
    }

    /**
     * @return void
     */
    public function testCreateReturnCreatesReturnUsingSalesOrderItemUuidInsteadOfId(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);
        $itemTransfer = $orderTransfer->getItems()->getIterator()->current();

        $this->tester->setItemState($itemTransfer->getIdSalesOrderItem(), static::SHIPPED_STATE_NAME);

        $returnItemTransfer = (new ReturnItemTransfer())
            ->setOrderItem((new ItemTransfer())->setUuid($itemTransfer->getUuid()));

        $returnCreateRequestTransfer = (new ReturnCreateRequestTransfer())
            ->setCustomer($orderTransfer->getCustomer())
            ->setStore($orderTransfer->getStore())
            ->addReturnItem($returnItemTransfer);

        // Act
        $returnResponseTransfer = $this->tester
            ->getFacade()
            ->createReturn($returnCreateRequestTransfer);

        // Assert
        $this->assertTrue($returnResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testCreateReturnCreatesReturnWithDuplicatedOrderItems(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);
        $itemTransfer = $orderTransfer->getItems()->getIterator()->current();

        $this->tester->setItemState($itemTransfer->getIdSalesOrderItem(), static::SHIPPED_STATE_NAME);

        $returnItemTransfer = (new ReturnItemTransfer())
            ->setOrderItem((new ItemTransfer())->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItem()));

        $returnCreateRequestTransfer = (new ReturnCreateRequestTransfer())
            ->setCustomer($orderTransfer->getCustomer())
            ->setStore($orderTransfer->getStore())
            ->addReturnItem($returnItemTransfer)
            ->addReturnItem($returnItemTransfer);

        // Act
        $returnResponseTransfer = $this->tester
            ->getFacade()
            ->createReturn($returnCreateRequestTransfer);

        // Assert
        $this->assertFalse($returnResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_CREATE_RETURN_ITEM_ERROR,
            $returnResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testCreateReturnCreatesReturnWithoutIdOrUuidOrderItem(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);

        $returnItemTransfer = (new ReturnItemTransfer())
            ->setOrderItem(new ItemTransfer());

        $returnCreateRequestTransfer = (new ReturnCreateRequestTransfer())
            ->setCustomer($orderTransfer->getCustomer())
            ->setStore($orderTransfer->getStore())
            ->addReturnItem($returnItemTransfer);

        // Act
        $returnResponseTransfer = $this->tester
            ->getFacade()
            ->createReturn($returnCreateRequestTransfer);

        // Assert
        $this->assertFalse($returnResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_CREATE_RETURN_ITEM_REQUIRED_FIELDS_ERROR,
            $returnResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testCreateReturnCreatesReturnWithInvalidStoreName(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);
        $itemTransfer = $orderTransfer->getItems()->getIterator()->current();

        $this->tester->setItemState($itemTransfer->getIdSalesOrderItem(), static::SHIPPED_STATE_NAME);

        $returnItemTransfer = (new ReturnItemTransfer())
            ->setOrderItem((new ItemTransfer())->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItem()));

        $returnCreateRequestTransfer = (new ReturnCreateRequestTransfer())
            ->setCustomer($orderTransfer->getCustomer())
            ->setStore(static::FAKE_STORE_NAME)
            ->addReturnItem($returnItemTransfer);

        // Act
        $returnResponseTransfer = $this->tester
            ->getFacade()
            ->createReturn($returnCreateRequestTransfer);

        // Assert
        $this->assertFalse($returnResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_CREATE_RETURN_STORE_ERROR,
            $returnResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testCreateReturnCreatesReturnWithItemsInReturnableStates(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        $firstOrderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);
        $secondOrderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);

        $firstItemTransfer = $firstOrderTransfer->getItems()->getIterator()->current();
        $secondItemTransfer = $secondOrderTransfer->getItems()->getIterator()->current();

        $this->tester->setItemState($firstItemTransfer->getIdSalesOrderItem(), static::SHIPPED_STATE_NAME);
        $this->tester->setItemState($secondItemTransfer->getIdSalesOrderItem(), static::DELIVERED_STATE_NAME);

        $firstReturnItemTransfer = (new ReturnItemTransfer())
            ->setOrderItem((new ItemTransfer())->setIdSalesOrderItem($firstItemTransfer->getIdSalesOrderItem()));

        $secondReturnItemTransfer = (new ReturnItemTransfer())
            ->setOrderItem((new ItemTransfer())->setIdSalesOrderItem($secondItemTransfer->getIdSalesOrderItem()));

        $returnCreateRequestTransfer = (new ReturnCreateRequestTransfer())
            ->setCustomer($customerTransfer)
            ->setStore($firstOrderTransfer->getStore())
            ->addReturnItem($firstReturnItemTransfer)
            ->addReturnItem($secondReturnItemTransfer);

        // Act
        $returnResponseTransfer = $this->tester
            ->getFacade()
            ->createReturn($returnCreateRequestTransfer);

        // Assert
        $this->assertTrue($returnResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testCreateReturnCreatesReturnWithItemInNotReturnableState(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);
        $itemTransfer = $orderTransfer->getItems()->getIterator()->current();

        $this->tester->setItemState($itemTransfer->getIdSalesOrderItem(), static::FAKE_STATE_NAME);

        $returnItemTransfer = (new ReturnItemTransfer())
            ->setOrderItem((new ItemTransfer())->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItem()));

        $returnCreateRequestTransfer = (new ReturnCreateRequestTransfer())
            ->setCustomer($orderTransfer->getCustomer())
            ->setStore($orderTransfer->getStore())
            ->addReturnItem($returnItemTransfer);

        // Act
        $returnResponseTransfer = $this->tester
            ->getFacade()
            ->createReturn($returnCreateRequestTransfer);

        // Assert
        $this->assertFalse($returnResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_CREATE_RETURN_RETURNABLE_ITEM_ERROR,
            $returnResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testCreateReturnThrowsExceptionWithoutReturnItems(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);

        $returnCreateRequestTransfer = (new ReturnCreateRequestTransfer())
            ->setCustomer($orderTransfer->getCustomer())
            ->setStore($orderTransfer->getStore())
            ->setReturnItems(new ArrayObject());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getFacade()
            ->createReturn($returnCreateRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreateReturnThrowsExceptionWithoutStore(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);
        $itemTransfer = $orderTransfer->getItems()->getIterator()->current();

        $this->tester->setItemState($itemTransfer->getIdSalesOrderItem(), static::SHIPPED_STATE_NAME);

        $returnItemTransfer = (new ReturnItemTransfer())
            ->setOrderItem((new ItemTransfer())->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItem()));

        $returnCreateRequestTransfer = (new ReturnCreateRequestTransfer())
            ->setCustomer($orderTransfer->getCustomer())
            ->setStore(null)
            ->addReturnItem($returnItemTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getFacade()
            ->createReturn($returnCreateRequestTransfer);
    }

    /**
     * @return \Spryker\Zed\SalesReturn\SalesReturnConfig
     */
    protected function getConfig(): SalesReturnConfig
    {
        return new SalesReturnConfig();
    }
}
