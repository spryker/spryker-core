<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Sales\Business\Model\OrderItemSplit\Validation;

use SprykerFeature\Zed\Sales\Business\Model\Split\Item;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemOption;

class ItemSplitTest extends \PHPUnit_Framework_TestCase
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
        'group_key'

    ];

    /**
     * @var array
     */
    private $notCopiedOrderItemOptionFields = [
        'created_at',
        'updated_at',
        'fk_sales_order_item'
    ];

    public function testIsOrderItemDataCopied()
    {
        $spySalesOrderItem = $this->createOrderItem();

        $itemSplit = $this->createOrderItemSplitter($spySalesOrderItem, 4);
        $splitResponse = $itemSplit->split($orderItemId = 1, $quantity = 1);

        $this->assertTrue($splitResponse->getSuccess());
        $this->assertNotEmpty($splitResponse->getSuccessMessage());

        $createdCopy = $spySalesOrderItem->getCreatedCopy();
        $this->assertEquals(1, $createdCopy->getQuantity());
        $this->assertEquals(4, $spySalesOrderItem->getQuantity());
        $this->assertEquals(Item::SPLIT_MARKER . $spySalesOrderItem->getGroupKey(), $createdCopy->getGroupKey());

        $oldSalesOrderItemArray = $spySalesOrderItem->toArray();
        $copyofItemSalesOrderItemArray = $createdCopy->toArray();

        $oldSalesOrderItemArray = $this->filterOutNotCopiedFields(
            $oldSalesOrderItemArray,
            $this->notCopiedOrderItemFields
        );
        $copyofItemSalesOrderItemArray = $this->filterOutNotCopiedFields(
            $copyofItemSalesOrderItemArray,
            $this->notCopiedOrderItemFields
        );

        $this->assertEquals($oldSalesOrderItemArray, $copyofItemSalesOrderItemArray);

        $options = $spySalesOrderItem->getOptions();

        foreach ($options as $option) {
             $oldOption =  $this->filterOutNotCopiedFields(
                 $option->toArray(),
                 $this->notCopiedOrderItemOptionFields
             );
             $copyOfOptions = $this->filterOutNotCopiedFields(
                 $option->getCreatedCopy()->toArray(),
                 $this->notCopiedOrderItemOptionFields
             );

             $this->assertEquals($oldOption, $copyOfOptions);
        }
    }


    /**
     * @return OrderItem
     */
    protected function createOrderItemSplitter(OrderItemSpy $orderItem, $quantityForOld)
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

        $itemSplit = new Item($validatorMock, $salesQueryContainerMock, $calculatorMock);
        $itemSplit->setDatabaseConnection($databaseConnectionMock);

        return $itemSplit;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createValidatorMock()
    {
        $validatorMock = $this
            ->getMockBuilder(
                'SprykerFeature\Zed\Sales\Business\Model\Split\Validation\ValidatorInterface',
                ['validate']
            )
            ->disableOriginalConstructor()
            ->getMock();
        return $validatorMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createQueryContainerMock()
    {
        return $this
            ->getMockBuilder(
                'SprykerFeature\Zed\Sales\Persistence\SalesQueryContainerInterface',
                ['querySalesOrderItem']
            )
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createSalesOrderMock()
    {
        $salesOrderItemQueryMock = $this
            ->getMockBuilder('SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderQuery')
            ->setMethods(['findOneByIdSalesOrderItem'])
            ->disableOriginalConstructor()
            ->getMock();
        return $salesOrderItemQueryMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createCalculatorMock()
    {
        $calculatorMock = $this
            ->getMockBuilder(
                'SprykerFeature\Zed\Sales\Business\Model\Split\CalculatorInterface',
                ['calculateQuantityAmountLeft']
            )
            ->disableOriginalConstructor()
            ->getMock();
        return $calculatorMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createDatabaseConnectionMock()
    {
        $databaseConnectionMock = $this
            ->getMockBuilder('Propel\Runtime\Connection\ConnectionInterface')
            ->getMock();
        return $databaseConnectionMock;
    }

    /**
     * @param $salesOrderItems
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
     * @return OrderItemSpy
     */
    protected function createOrderItem()
    {
        $spySalesOrderItem = new OrderItemSpy();
        $spySalesOrderItem->setIdSalesOrderItem(1);
        $spySalesOrderItem->setQuantity(5);
        $spySalesOrderItem->setFkSalesOrder(1);
        $spySalesOrderItem->setGroupKey(123);
        $spySalesOrderItem->setName('123');
        $spySalesOrderItem->setSku('A');
        $spySalesOrderItem->setGrossPrice(100);
        $spySalesOrderItem->setPriceToPay(125);

        $spySalesOrderItemOption = new OrderItemOptionSpy();
        $spySalesOrderItemOption->setLabelOptionType('X');
        $spySalesOrderItemOption->setLabelOptionValue('Y');
        $spySalesOrderItemOption->setGrossPrice(5);
        $spySalesOrderItemOption->setPriceToPay(15);

        $spySalesOrderItem->addOption($spySalesOrderItemOption);

        $spySalesOrderItemOption = new OrderItemOptionSpy();
        $spySalesOrderItemOption->setLabelOptionType('XX');
        $spySalesOrderItemOption->setLabelOptionValue('YY');
        $spySalesOrderItemOption->setGrossPrice(30);
        $spySalesOrderItemOption->setPriceToPay(35);
        $spySalesOrderItemOption->setTaxPercentage(15);

        $spySalesOrderItem->addOption($spySalesOrderItemOption);

        return $spySalesOrderItem;
    }
}

trait SpyTrait
{
    /**
     * @var SpySalesOrderItem
     */
    protected $propelModelCopy;

    /**
     * @param bool|false $deepCopy
     *
     * @return SpySalesOrderItem
     */
    public function copy($deepCopy = false)
    {
        $this->propelModelCopy = parent::copy($deepCopy);

        return $this->propelModelCopy;
    }

    /**
     * @return SpySalesOrderItem
     */
    public function getCreatedCopy()
    {
        return $this->propelModelCopy;
    }
}

class OrderItemSpy extends SpySalesOrderItem
{
    use SpyTrait;
}

class OrderItemOptionSpy extends SpySalesOrderItemOption
{
    use SpyTrait;
}

