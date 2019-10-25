<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesSplit\Business\Model;

use Codeception\Test\Unit;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;
use Spryker\Zed\SalesSplit\Business\Model\CalculatorInterface;
use Spryker\Zed\SalesSplit\Business\Model\OrderItemSplit;
use Spryker\Zed\SalesSplit\Business\Model\Validation\ValidatorInterface;
use SprykerTest\Zed\SalesSplit\Business\Model\Fixtures\SpySalesOrderItemMock;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesSplit
 * @group Business
 * @group Model
 * @group OrderItemSplitTest
 * Add your own group annotations below this line
 */
class OrderItemSplitTest extends Unit
{
    /**
     * @var array
     */
    private $notCopiedOrderItemFields = [
        'id_sales_order_item',
        'last_state_change',
        'quantity',
        'created_at',
        'updated_at',
        'group_key',

    ];

    /**
     * @return void
     */
    public function testIsOrderItemDataCopied()
    {
        $spySalesOrderItem = $this->createOrderItem();
        $itemSplit = $this->createOrderItemSplitter($spySalesOrderItem, 4);
        $orderItemId = 1;
        $quantity = 1;
        $splitResponse = $itemSplit->split($orderItemId, $quantity);

        $this->assertTrue($splitResponse->getSuccess());
        $this->assertNotEmpty($splitResponse->getSuccessMessage());

        $createdCopy = $spySalesOrderItem->getCreatedCopy();
        $this->assertEquals(1, $createdCopy->getQuantity());
        $this->assertEquals(4, $spySalesOrderItem->getQuantity());
        $this->assertEquals(OrderItemSplit::SPLIT_MARKER . $spySalesOrderItem->getGroupKey(), $createdCopy->getGroupKey());

        $oldSalesOrderItemArray = $spySalesOrderItem->toArray();
        $copyOfItemSalesOrderItemArray = $createdCopy->toArray();

        $oldSalesOrderItemArray = $this->filterOutNotCopiedFields(
            $oldSalesOrderItemArray,
            $this->notCopiedOrderItemFields
        );
        $copyOfItemSalesOrderItemArray = $this->filterOutNotCopiedFields(
            $copyOfItemSalesOrderItemArray,
            $this->notCopiedOrderItemFields
        );

        $this->assertEquals($oldSalesOrderItemArray, $copyOfItemSalesOrderItemArray);
    }

    /**
     * @param \SprykerTest\Zed\SalesSplit\Business\Model\Fixtures\SpySalesOrderItemMock $orderItem
     * @param int $quantityForOld
     *
     * @return \Spryker\Zed\SalesSplit\Business\Model\OrderItemSplit
     */
    protected function createOrderItemSplitter(SpySalesOrderItemMock $orderItem, $quantityForOld)
    {
        $validatorMock = $this->createValidatorMock();
        $salesQueryContainerMock = $this->createQueryContainerMock();
        $salesOrderItemQueryMock = $this->createSalesOrderMock();
        $calculatorMock = $this->createCalculatorMock();
        $databaseConnectionMock = $this->createDatabaseConnectionMock();

        $validatorMock
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $salesOrderItemQueryMock
            ->expects($this->once())
            ->method('findOneByIdSalesOrderItem')
            ->will($this->returnValue($orderItem));

        $salesQueryContainerMock
            ->expects($this->once())
            ->method('querySalesOrderItem')
            ->will($this->returnValue($salesOrderItemQueryMock));

        $calculatorMock
            ->expects($this->once())
            ->method('calculateQuantityAmountLeft')
            ->will($this->returnValue($quantityForOld));

        $itemSplit = new OrderItemSplit($validatorMock, $salesQueryContainerMock, $calculatorMock);
        $itemSplit->setDatabaseConnection($databaseConnectionMock);

        return $itemSplit;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesSplit\Business\Model\Validation\ValidatorInterface
     */
    protected function createValidatorMock()
    {
        $validatorMock = $this
            ->getMockBuilder(ValidatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $validatorMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected function createQueryContainerMock()
    {
        return $this
            ->getMockBuilder(SalesQueryContainerInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function createSalesOrderMock()
    {
        $salesOrderItemQueryMock = $this
            ->getMockBuilder(SpySalesOrderQuery::class)
            ->setMethods(['findOneByIdSalesOrderItem'])
            ->disableOriginalConstructor()
            ->getMock();

        return $salesOrderItemQueryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesSplit\Business\Model\CalculatorInterface
     */
    protected function createCalculatorMock()
    {
        $calculatorMock = $this
            ->getMockBuilder(CalculatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $calculatorMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Propel\Runtime\Connection\ConnectionInterface
     */
    protected function createDatabaseConnectionMock()
    {
        $databaseConnectionMock = $this
            ->getMockBuilder(ConnectionInterface::class)
            ->getMock();

        return $databaseConnectionMock;
    }

    /**
     * @param array $salesOrderItems
     * @param array $notCopiedFields
     *
     * @return array
     */
    protected function filterOutNotCopiedFields($salesOrderItems, $notCopiedFields)
    {
        foreach ($salesOrderItems as $key => $value) {
            if (in_array($key, $notCopiedFields)) {
                unset($salesOrderItems[$key]);
            }
        }

        return $salesOrderItems;
    }

    /**
     * @return \SprykerTest\Zed\SalesSplit\Business\Model\Fixtures\SpySalesOrderItemMock
     */
    protected function createOrderItem()
    {
        $spySalesOrderItem = new SpySalesOrderItemMock();
        $spySalesOrderItem->setIdSalesOrderItem(1);
        $spySalesOrderItem->setQuantity(5);
        $spySalesOrderItem->setFkSalesOrder(1);
        $spySalesOrderItem->setGroupKey(123);
        $spySalesOrderItem->setName('123');
        $spySalesOrderItem->setSku('A');
        $spySalesOrderItem->setGrossPrice(100);

        return $spySalesOrderItem;
    }
}
