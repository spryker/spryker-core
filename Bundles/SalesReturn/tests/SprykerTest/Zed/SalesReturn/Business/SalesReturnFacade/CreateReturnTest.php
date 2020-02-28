<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesReturn\Business\SalesReturnFacade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CreateReturnRequestTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

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

    /**
     * @uses \Spryker\Zed\SalesReturn\Business\Writer\ReturnWriter::GLOSSARY_KEY_CREATE_RETURN_ITEM_REQUIRED_FIELDS_ERROR
     */
    protected const GLOSSARY_KEY_CREATE_RETURN_ITEM_REQUIRED_FIELDS_ERROR = 'return.create_return.validation.required_item_fields_error';

    /**
     * @uses \Spryker\Zed\SalesReturn\Business\Validator\ReturnValidator::GLOSSARY_KEY_CREATE_RETURN_ITEM_ERROR
     */
    protected const GLOSSARY_KEY_CREATE_RETURN_ITEM_ERROR = 'return.create_return.validation.items_error';

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
    }

    /**
     * @return void
     */
    public function testCreateReturnCreatesReturn(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByCustomer(static::DEFAULT_OMS_PROCESS_NAME);
        $itemTransfer = $orderTransfer->getItems()->getIterator()->current();

        $returnItemTransfer = (new ReturnItemTransfer())
            ->setReason(static::FAKE_RETURN_REASON)
            ->setOrderItem((new ItemTransfer())->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItem()));

        $createReturnRequestTransfer = (new CreateReturnRequestTransfer())
            ->setCustomer($orderTransfer->getCustomer())
            ->setStore($orderTransfer->getStore())
            ->addReturnItem($returnItemTransfer);

        // Act
        $returnResponseTransfer = $this->tester
            ->getFacade()
            ->createReturn($createReturnRequestTransfer);

        // Assert
        $returnTransfer = $returnResponseTransfer->getReturn();
        $returnItemTransfer = $returnTransfer->getReturnItems()->getIterator()->current();

        $this->assertTrue($returnResponseTransfer->getIsSuccessful());
        $this->assertSame($orderTransfer->getStore(), $returnTransfer->getStore());
        $this->assertSame(
            $orderTransfer->getCustomer()->getCustomerReference(),
            $returnTransfer->getCustomer()->getCustomerReference()
        );

        $this->assertSame(static::FAKE_RETURN_REASON, $returnItemTransfer->getReason());
        $this->assertSame($itemTransfer->getIdSalesOrderItem(), $returnItemTransfer->getOrderItem()->getIdSalesOrderItem());
        $this->assertSame(
            $orderTransfer->getCustomer()->getCustomerReference() . '-R1',
            $returnTransfer->getReturnReference()
        );
    }

    /**
     * @return void
     */
    public function testCreateReturnCreatesReturnWithEmptyReturnReason(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByCustomer(static::DEFAULT_OMS_PROCESS_NAME);
        $itemTransfer = $orderTransfer->getItems()->getIterator()->current();

        $returnItemTransfer = (new ReturnItemTransfer())
            ->setReason(null)
            ->setOrderItem((new ItemTransfer())->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItem()));

        $createReturnRequestTransfer = (new CreateReturnRequestTransfer())
            ->setCustomer($orderTransfer->getCustomer())
            ->setStore($orderTransfer->getStore())
            ->addReturnItem($returnItemTransfer);

        // Act
        $returnResponseTransfer = $this->tester
            ->getFacade()
            ->createReturn($createReturnRequestTransfer);

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

        $firstOrderTransfer = $this->tester->createOrderByCustomer(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);
        $secondOrderTransfer = $this->tester->createOrderByCustomer(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);

        $firstItemTransfer = $firstOrderTransfer->getItems()->getIterator()->current();
        $secondItemTransfer = $secondOrderTransfer->getItems()->getIterator()->current();

        $firstReturnItemTransfer = (new ReturnItemTransfer())
            ->setOrderItem((new ItemTransfer())->setIdSalesOrderItem($firstItemTransfer->getIdSalesOrderItem()));

        $secondReturnItemTransfer = (new ReturnItemTransfer())
            ->setOrderItem((new ItemTransfer())->setIdSalesOrderItem($secondItemTransfer->getIdSalesOrderItem()));

        $createReturnRequestTransfer = (new CreateReturnRequestTransfer())
            ->setCustomer($customerTransfer)
            ->setStore($firstOrderTransfer->getStore())
            ->addReturnItem($firstReturnItemTransfer)
            ->addReturnItem($secondReturnItemTransfer);

        // Act
        $returnResponseTransfer = $this->tester
            ->getFacade()
            ->createReturn($createReturnRequestTransfer);

        // Assert
        $returnTransfer = $returnResponseTransfer->getReturn();

        $this->assertTrue($returnResponseTransfer->getIsSuccessful());
        $this->assertCount(2, $returnTransfer->getReturnItems());
    }

    /**
     * @return void
     */
    public function testCreateReturnCreatesReturnFromSeveralOrdersFromDifferentCustomers(): void
    {
        // Arrange
        $firstOrderTransfer = $this->tester->createOrderByCustomer(static::DEFAULT_OMS_PROCESS_NAME);
        $secondOrderTransfer = $this->tester->createOrderByCustomer(static::DEFAULT_OMS_PROCESS_NAME);

        $firstItemTransfer = $firstOrderTransfer->getItems()->getIterator()->current();
        $secondItemTransfer = $secondOrderTransfer->getItems()->getIterator()->current();

        $firstReturnItemTransfer = (new ReturnItemTransfer())
            ->setOrderItem((new ItemTransfer())->setIdSalesOrderItem($firstItemTransfer->getIdSalesOrderItem()));

        $secondReturnItemTransfer = (new ReturnItemTransfer())
            ->setOrderItem((new ItemTransfer())->setIdSalesOrderItem($secondItemTransfer->getIdSalesOrderItem()));

        $createReturnRequestTransfer = (new CreateReturnRequestTransfer())
            ->setCustomer($firstOrderTransfer->getCustomer())
            ->setStore($firstOrderTransfer->getStore())
            ->addReturnItem($firstReturnItemTransfer)
            ->addReturnItem($secondReturnItemTransfer);

        // Act
        $returnResponseTransfer = $this->tester
            ->getFacade()
            ->createReturn($createReturnRequestTransfer);

        // Assert
        $this->assertFalse($returnResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_CREATE_RETURN_ITEM_ERROR,
            $returnResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testCreateReturnCreatesReturnUsingSalesOrderItemUuidInsteadOfId(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByCustomer(static::DEFAULT_OMS_PROCESS_NAME);
        $itemTransfer = $orderTransfer->getItems()->getIterator()->current();

        $returnItemTransfer = (new ReturnItemTransfer())
            ->setOrderItem((new ItemTransfer())->setUuid($itemTransfer->getUuid()));

        $createReturnRequestTransfer = (new CreateReturnRequestTransfer())
            ->setCustomer($orderTransfer->getCustomer())
            ->setStore($orderTransfer->getStore())
            ->addReturnItem($returnItemTransfer);

        // Act
        $returnResponseTransfer = $this->tester
            ->getFacade()
            ->createReturn($createReturnRequestTransfer);

        // Assert
        $this->assertTrue($returnResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testCreateReturnCreatesReturnWithDuplicatedOrderItems(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByCustomer(static::DEFAULT_OMS_PROCESS_NAME);
        $itemTransfer = $orderTransfer->getItems()->getIterator()->current();

        $returnItemTransfer = (new ReturnItemTransfer())
            ->setOrderItem((new ItemTransfer())->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItem()));

        $createReturnRequestTransfer = (new CreateReturnRequestTransfer())
            ->setCustomer($orderTransfer->getCustomer())
            ->setStore($orderTransfer->getStore())
            ->addReturnItem($returnItemTransfer)
            ->addReturnItem($returnItemTransfer);

        // Act
        $returnResponseTransfer = $this->tester
            ->getFacade()
            ->createReturn($createReturnRequestTransfer);

        // Assert
        $this->assertFalse($returnResponseTransfer->getIsSuccessful());
        $this->assertEquals(
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
        $orderTransfer = $this->tester->createOrderByCustomer(static::DEFAULT_OMS_PROCESS_NAME);

        $returnItemTransfer = (new ReturnItemTransfer())
            ->setOrderItem(new ItemTransfer());

        $createReturnRequestTransfer = (new CreateReturnRequestTransfer())
            ->setCustomer($orderTransfer->getCustomer())
            ->setStore($orderTransfer->getStore())
            ->addReturnItem($returnItemTransfer);

        // Act
        $returnResponseTransfer = $this->tester
            ->getFacade()
            ->createReturn($createReturnRequestTransfer);

        // Assert
        $this->assertFalse($returnResponseTransfer->getIsSuccessful());
        $this->assertEquals(
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
        $orderTransfer = $this->tester->createOrderByCustomer(static::DEFAULT_OMS_PROCESS_NAME);
        $itemTransfer = $orderTransfer->getItems()->getIterator()->current();

        $returnItemTransfer = (new ReturnItemTransfer())
            ->setOrderItem((new ItemTransfer())->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItem()));

        $createReturnRequestTransfer = (new CreateReturnRequestTransfer())
            ->setCustomer($orderTransfer->getCustomer())
            ->setStore(static::FAKE_STORE_NAME)
            ->addReturnItem($returnItemTransfer);

        // Act
        $returnResponseTransfer = $this->tester
            ->getFacade()
            ->createReturn($createReturnRequestTransfer);

        // Assert
        $this->assertFalse($returnResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_CREATE_RETURN_STORE_ERROR,
            $returnResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testCreateReturnThrowsExceptionWithoutReturnItems(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByCustomer(static::DEFAULT_OMS_PROCESS_NAME);

        $createReturnRequestTransfer = (new CreateReturnRequestTransfer())
            ->setCustomer($orderTransfer->getCustomer())
            ->setStore($orderTransfer->getStore())
            ->setReturnItems(new ArrayObject());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getFacade()
            ->createReturn($createReturnRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreateReturnThrowsExceptionWithoutStore(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByCustomer(static::DEFAULT_OMS_PROCESS_NAME);
        $itemTransfer = $orderTransfer->getItems()->getIterator()->current();

        $returnItemTransfer = (new ReturnItemTransfer())
            ->setOrderItem((new ItemTransfer())->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItem()));

        $createReturnRequestTransfer = (new CreateReturnRequestTransfer())
            ->setCustomer($orderTransfer->getCustomer())
            ->setStore(null)
            ->addReturnItem($returnItemTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getFacade()
            ->createReturn($createReturnRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreateReturnThrowsExceptionWithoutCustomer(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByCustomer(static::DEFAULT_OMS_PROCESS_NAME);
        $itemTransfer = $orderTransfer->getItems()->getIterator()->current();

        $returnItemTransfer = (new ReturnItemTransfer())
            ->setOrderItem((new ItemTransfer())->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItem()));

        $createReturnRequestTransfer = (new CreateReturnRequestTransfer())
            ->setCustomer(null)
            ->setStore($orderTransfer->getStore())
            ->addReturnItem($returnItemTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getFacade()
            ->createReturn($createReturnRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreateReturnThrowsExceptionWithoutCustomerReference(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByCustomer(static::DEFAULT_OMS_PROCESS_NAME);
        $itemTransfer = $orderTransfer->getItems()->getIterator()->current();

        $returnItemTransfer = (new ReturnItemTransfer())
            ->setOrderItem((new ItemTransfer())->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItem()));

        $createReturnRequestTransfer = (new CreateReturnRequestTransfer())
            ->setCustomer(new CustomerTransfer())
            ->setStore($orderTransfer->getStore())
            ->addReturnItem($returnItemTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getFacade()
            ->createReturn($createReturnRequestTransfer);
    }
}
