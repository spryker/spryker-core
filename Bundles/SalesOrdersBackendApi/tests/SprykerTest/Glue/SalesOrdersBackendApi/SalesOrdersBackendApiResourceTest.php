<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\SalesOrdersBackendApi;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FilterFieldTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\OrderListFormatTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Glue\SalesOrdersBackendApi\SalesOrdersBackendApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group SalesOrdersBackendApi
 * @group SalesOrdersBackendApiResourceTest
 * Add your own group annotations below this line
 */
class SalesOrdersBackendApiResourceTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Sales\Persistence\Propel\QueryBuilder\OrderSearchFilterFieldQueryBuilder::SEARCH_TYPE_ITEM_SKU
     *
     * @var string
     */
    protected const SEARCH_TYPE_ITEM_SKU = 'itemSku';

    /**
     * @var string
     */
    protected const TEST_ITEM_SKU = 'TEST_ITEM_SKU';

    /**
     * @var \SprykerTest\Glue\SalesOrdersBackendApi\SalesOrdersBackendApiTester
     */
    protected SalesOrdersBackendApiTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([SalesOrdersBackendApiTester::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testGetOrderResourceCollectionShouldReturnEmptyCollectionWhenOrdersNotFound(): void
    {
        // Arrange
        $orderListTransfer = $this->createOrderListTransfer(static::TEST_ITEM_SKU);

        // Act
        $orderResourceCollectionTransfer = $this->tester->getSalesOrdersBackendApiResource()
            ->getOrderResourceCollection($orderListTransfer);

        // Assert
        $this->assertEmpty($orderResourceCollectionTransfer->getOrderResources());
        $this->assertEmpty($orderResourceCollectionTransfer->getOrders());
    }

    /**
     * @return void
     */
    public function testGetOrderResourceCollectionShouldReturnCollectionWithOrders(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createSaveOrderTransfer();
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $orderTransfer->getOrderItems()->getIterator()->current();

        $this->tester->createSaveOrderTransfer();

        $orderListTransfer = $this->createOrderListTransfer($itemTransfer->getSkuOrFail())
            ->setFilter((new FilterTransfer())->setOrderBy(OrderTransfer::ID_SALES_ORDER));

        // Act
        $orderResourceCollectionTransfer = $this->tester->getSalesOrdersBackendApiResource()
            ->getOrderResourceCollection($orderListTransfer);

        // Assert
        $this->assertCount(1, $orderResourceCollectionTransfer->getOrderResources());
        $this->assertCount(1, $orderResourceCollectionTransfer->getOrders());

        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $orderResourceTransfer */
        $orderResourceTransfer = $orderResourceCollectionTransfer->getOrderResources()->getIterator()->offsetGet(0);
        $this->assertSame(SalesOrdersBackendApiConfig::RESOURCE_SALES_ORDERS, $orderResourceTransfer->getType());
        $this->assertSame($orderTransfer->getOrderReference(), $orderResourceTransfer->getId());
    }

    /**
     * @param string $itemSku
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    protected function createOrderListTransfer(string $itemSku): OrderListTransfer
    {
        $filterFiledTransfer = (new FilterFieldTransfer())
            ->setType(static::SEARCH_TYPE_ITEM_SKU)
            ->setValue($itemSku);

        return (new OrderListTransfer())
            ->addFilterField($filterFiledTransfer)
            ->setFormat((new OrderListFormatTransfer())->setExpandWithItems(true));
    }
}
