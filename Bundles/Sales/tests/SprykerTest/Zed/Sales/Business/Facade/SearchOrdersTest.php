<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FilterFieldTransfer;
use Generated\Shared\Transfer\OrderListFormatTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\QueryJoinCollectionTransfer;
use Generated\Shared\Transfer\QueryJoinTransfer;
use Generated\Shared\Transfer\QueryWhereConditionTransfer;
use Spryker\Zed\Sales\SalesDependencyProvider;
use Spryker\Zed\SalesExtension\Dependency\Plugin\SearchOrderQueryExpanderPluginInterface;
use SprykerTest\Zed\Sales\SalesBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Facade
 * @group SearchOrdersTest
 * Add your own group annotations below this line
 */
class SearchOrdersTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var string
     */
    protected const FAKE_LIKE_ORDER_REFERENCE = 'fake-like-order-reference';

    /**
     * @see \Spryker\Shared\Sales\SalesConfig::ORDER_SEARCH_TYPES
     *
     * @var string
     */
    protected const FILTER_TYPE_ALL = 'all';

    /**
     * @uses \Spryker\Zed\Sales\Persistence\Propel\QueryBuilder\OrderSearchFilterFieldQueryBuilder::CONDITION_GROUP_ALL
     *
     * @var string
     */
    protected const CONDITION_GROUP_ALL = 'CONDITION_GROUP_ALL';

    /**
     * @var string
     */
    protected const COLUMN_FULL_NAME = "CONCAT(first_name, ' ', last_name)";

    /**
     * @uses \Spryker\Zed\Sales\Persistence\Propel\QueryBuilder\OrderSearchFilterFieldQueryBuilder::SEARCH_TYPE_ITEM_UUIDS
     *
     * @var string
     */
    protected const SEARCH_TYPE_ITEM_UUIDS = 'itemUuids';

    /**
     * @uses \Spryker\Zed\Sales\Persistence\Propel\QueryBuilder\OrderSearchFilterFieldQueryBuilder::DELIMITER_COLLECTION_TYPE_VALUE
     *
     * @var string
     */
    protected const DELIMITER_COLLECTION_TYPE_VALUE = ',';

    /**
     * @var \SprykerTest\Zed\Sales\SalesBusinessTester
     */
    protected SalesBusinessTester $tester;

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
    public function testSearchOrdersCheckLikeConditionWithLowerCase(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);

        $orderListTransfer = (new OrderListTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setFormat((new OrderListFormatTransfer())->setExpandWithItems(false))
            ->setPagination((new PaginationTransfer())->setPage(1)->setMaxPerPage(10))
            ->addFilterField((new FilterFieldTransfer())->setType(static::FILTER_TYPE_ALL)->setValue(mb_strtolower($orderTransfer->getOrderReference())));

        // Act
        $storedOrderListTransfer = $this->tester
            ->getFacade()
            ->searchOrders($orderListTransfer);

        // Assert
        $this->assertCount(1, $storedOrderListTransfer->getOrders());
    }

    /**
     * @return void
     */
    public function testSearchOrdersCheckLikeConditionWithFirstLetterInUpperCase(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);

        $orderListTransfer = (new OrderListTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setFormat((new OrderListFormatTransfer())->setExpandWithItems(false))
            ->setPagination((new PaginationTransfer())->setPage(1)->setMaxPerPage(10))
            ->addFilterField((new FilterFieldTransfer())->setType(static::FILTER_TYPE_ALL)->setValue(ucfirst(mb_strtolower($orderTransfer->getOrderReference()))));

        // Act
        $storedOrderListTransfer = $this->tester
            ->getFacade()
            ->searchOrders($orderListTransfer);

        // Assert
        $this->assertCount(1, $storedOrderListTransfer->getOrders());
    }

    /**
     * @return void
     */
    public function testSearchOrdersCheckLikeConditionWithFakeOrderReference(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);

        $orderListTransfer = (new OrderListTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setFormat((new OrderListFormatTransfer())->setExpandWithItems(false))
            ->setPagination((new PaginationTransfer())->setPage(1)->setMaxPerPage(10))
            ->addFilterField((new FilterFieldTransfer())->setType(static::FILTER_TYPE_ALL)->setValue(static::FAKE_LIKE_ORDER_REFERENCE));

        // Act
        $storedOrderListTransfer = $this->tester
            ->getFacade()
            ->searchOrders($orderListTransfer);

        // Assert
        $this->assertCount(0, $storedOrderListTransfer->getOrders());
    }

    /**
     * @return void
     */
    public function testSearchOrdersShouldFilterByConcatFields(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);

        $queryJoinTransfer = (new QueryJoinTransfer())->addQueryWhereCondition(
            (new QueryWhereConditionTransfer())
                ->setColumn(static::COLUMN_FULL_NAME)
                ->setValue($customerTransfer->getFirstName())
                ->setMergeWithCondition(static::CONDITION_GROUP_ALL),
        );

        $queryJoinCollectionTransfer = (new QueryJoinCollectionTransfer())->addQueryJoin($queryJoinTransfer);

        $this->tester->setDependency(SalesDependencyProvider::PLUGINS_ORDER_SEARCH_QUERY_EXPANDER, [
            $this->createSearchOrderQueryExpanderPlugin($queryJoinCollectionTransfer),
        ]);

        $orderListTransfer = (new OrderListTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setFormat((new OrderListFormatTransfer())->setExpandWithItems(false))
            ->setPagination((new PaginationTransfer())->setPage(1)->setMaxPerPage(10))
            ->addFilterField((new FilterFieldTransfer())->setType(static::FILTER_TYPE_ALL)->setValue($customerTransfer->getCustomerReference()));

        // Act
        $storedOrderListTransfer = $this->tester
            ->getFacade()
            ->searchOrders($orderListTransfer);

        // Assert
        $this->assertCount(1, $storedOrderListTransfer->getOrders());
    }

    /**
     * @return void
     */
    public function testSearchOrdersShouldFilterByItemUuidsField(): void
    {
        // Arrange
        if (!$this->tester->hasItemUuidField()) {
            $this->markTestSkipped('This test is not suitable for order items without UUID');
        }

        $customerTransfer = $this->tester->haveCustomer();

        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $orderTransfer->getItems()->getIterator()->current();

        $secondOrderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);
        /** @var \Generated\Shared\Transfer\ItemTransfer $secondItemTransfer */
        $secondItemTransfer = $secondOrderTransfer->getItems()->getIterator()->current();

        $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);

        $expectedSalesOrderIds = [$orderTransfer->getIdSalesOrder(), $secondOrderTransfer->getIdSalesOrder()];

        $filterTransfer = (new FilterFieldTransfer())
            ->setType(static::SEARCH_TYPE_ITEM_UUIDS)
            ->setValue(implode(static::DELIMITER_COLLECTION_TYPE_VALUE, [$itemTransfer->getUuid(), $secondItemTransfer->getUuid()]));

        $orderListTransfer = (new OrderListTransfer())
            ->setFormat((new OrderListFormatTransfer()))
            ->addFilterField($filterTransfer);

        // Act
        $resultOrderListTransfer = $this->tester
            ->getFacade()
            ->searchOrders($orderListTransfer);

        // Assert
        $this->assertCount(2, $resultOrderListTransfer->getOrders());

        /** @var \Generated\Shared\Transfer\OrderTransfer $resultOrderTransfer */
        $resultOrderTransfer = $resultOrderListTransfer->getOrders()->getIterator()->offsetGet(0);
        $this->assertTrue(in_array($resultOrderTransfer->getIdSalesOrder(), $expectedSalesOrderIds));

        /** @var \Generated\Shared\Transfer\OrderTransfer $secondResultOrderTransfer */
        $secondResultOrderTransfer = $resultOrderListTransfer->getOrders()->getIterator()->offsetGet(0);
        $this->assertTrue(in_array($secondResultOrderTransfer->getIdSalesOrder(), $expectedSalesOrderIds));
    }

    /**
     * @param \Generated\Shared\Transfer\QueryJoinCollectionTransfer $queryJoinCollectionTransfer
     *
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\SearchOrderQueryExpanderPluginInterface
     */
    protected function createSearchOrderQueryExpanderPlugin(QueryJoinCollectionTransfer $queryJoinCollectionTransfer): SearchOrderQueryExpanderPluginInterface
    {
        $searchOrderQueryExpanderPluginMock = $this->getMockBuilder(SearchOrderQueryExpanderPluginInterface::class)
            ->getMock();

        $searchOrderQueryExpanderPluginMock->method('isApplicable')
            ->willReturn(true);

        $searchOrderQueryExpanderPluginMock->method('expand')
            ->willReturn($queryJoinCollectionTransfer);

        return $searchOrderQueryExpanderPluginMock;
    }
}
