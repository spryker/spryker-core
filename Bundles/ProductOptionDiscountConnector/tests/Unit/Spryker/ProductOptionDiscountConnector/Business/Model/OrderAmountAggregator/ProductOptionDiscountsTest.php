<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Orm\Zed\Sales\Persistence\SpySalesDiscount;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\ProductOptionDiscounts;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use Orm\Zed\Sales\Persistence\SpySalesDiscountQuery;

class ProductOptionDiscountsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testProductOptionDiscountWhenOrderHaveShouldHydrateToCalculatedDiscounts()
    {
        $productOptionAggregator = $this->createProductOptionsAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $productOptionAggregator->aggregate($orderTransfer);

        $productoOptionTransfer = $orderTransfer->getItems()[0]->getProductOptions()[0];
        $this->assertEquals(100, $productoOptionTransfer->getCalculatedDiscounts()[0]->getSumGrossAmount());
    }

    /**
     * @return \Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\ProductOptionDiscounts
     */
    protected function createProductOptionsAggregator()
    {
        $discountQueryContainerMock = $this->createDiscountQueryContainer();

        $salesDiscountQueryMock = $this->createDiscountQueryMock();

        $objectColletion = new ObjectCollection();

        $salesDiscountEntity = new SpySalesDiscount();
        $salesDiscountEntity->setFkSalesOrderItem(1);
        $salesDiscountEntity->setAmount(100);
        $salesDiscountEntity->setFkSalesOrderItemOption(1);
        $objectColletion->append($salesDiscountEntity);

        $salesDiscountEntity = new SpySalesDiscount();
        $salesDiscountEntity->setFkSalesOrderItem(1);
        $salesDiscountEntity->setAmount(200);
        $salesDiscountEntity->setFkSalesOrderItemOption(1);
        $objectColletion->append($salesDiscountEntity);

        $salesDiscountQueryMock->expects($this->once())
            ->method('filterByFkSalesOrder')
            ->with($this->isType('integer'))
            ->willReturn($objectColletion);

        $discountQueryContainerMock->expects($this->once())
            ->method('querySalesDisount')
            ->willReturn($salesDiscountQueryMock);

        return new ProductOptionDiscounts($discountQueryContainerMock);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Sales\Persistence\SpySalesDiscountQuery
     */
    protected function createDiscountQueryMock()
    {
        return $this->getMockBuilder(SpySalesDiscountQuery::class)
            ->setMethods(['filterByFkSalesOrder'])
            ->disableArgumentCloning()
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected function createDiscountQueryContainer()
    {
        return $this->getMockBuilder(DiscountQueryContainerInterface::class)
            ->disableArgumentCloning()
            ->getMock();
    }
    
    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer->setIdSalesOrder(1);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setIdSalesOrderItem(1);

        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setIdSalesOrderItemOption(1);
        $productOptionTransfer->setQuantity(1);
        $itemTransfer->addProductOption($productOptionTransfer);

        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setIdSalesOrderItemOption(1);
        $productOptionTransfer->setQuantity(1);
        $itemTransfer->addProductOption($productOptionTransfer);

        $orderTransfer->addItem($itemTransfer);

        return $orderTransfer;
    }

}
