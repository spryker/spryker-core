<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Sales\Business\Model\OrderItemSplit\Validation;

use SprykerFeature\Zed\Sales\Business\Model\OrderItemSplit\ItemSplit;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;


class ItemSplitTest extends \PHPUnit_Framework_TestCase
{
    public function testItemSplitWithValidOrderItem()
    {
        $validatorMock = $this
            ->getMockBuilder('SprykerFeature\Zed\Sales\Business\Model\OrderItemSplit\Validation\ValidatorInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $salesQueryContainerMock = $this
            ->getMockBuilder('SprykerFeature\Zed\Sales\Persistence\SalesQueryContainerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $salesOrderItemQueryMock = $this
            ->getMockBuilder('SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem')
            ->disableOriginalConstructor()
            ->getMock();

        $calculatorMock = $this
            ->getMockBuilder('SprykerFeature\Zed\Sales\Business\Model\OrderItemSplit\CalculatorInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $validatorMock
            ->expects($this->once())
            ->method('validate')
            ->will(new \PHPUnit_Framework_MockObject_Stub_Return(true));

        $spySalesOrderItem = new SpySalesOrderItem();
        $spySalesOrderItem->setQuantity(5);

        $salesOrderItemQueryMock
            ->expects($this->once())
            ->method('findOneByIdSalesOrderItem')
            ->will(new \PHPUnit_Framework_MockObject_Stub_Return($spySalesOrderItem));

        $salesQueryContainerMock
            ->expects($this->once())
            ->method('querySalesOrderItem')
            ->will(new \PHPUnit_Framework_MockObject_Stub_Return($salesOrderItemQueryMock));

        $itemSplit = new ItemSplit($validatorMock, $salesQueryContainerMock, $calculatorMock);

        //$splitResponse = $itemSplit->split(1, 1);


    }
}
