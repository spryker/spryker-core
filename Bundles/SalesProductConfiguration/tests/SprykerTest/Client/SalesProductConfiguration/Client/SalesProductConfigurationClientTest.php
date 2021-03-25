<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesProductConfiguration\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\OrderBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer;
use Spryker\Client\SalesProductConfiguration\Expander\ItemExpander;
use Spryker\Client\SalesProductConfiguration\SalesProductConfigurationFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesProductConfiguration
 * @group Business
 * @group SalesProductConfigurationClientTest
 * Add your own group annotations below this line
 */
class SalesProductConfigurationClientTest extends Unit
{
    protected const TEST_CUSTOMER_REFERENCE = 'TEST_CUSTOMER_REFERENCE';
    protected const TEST_GROUP_KEY = 'TEST_GROUP_KEY';
    protected const TEST_ID_SALES_ORDER_ITEM = 1;
    protected const TEST_ORDER_REFERENCE = 'TEST_ORDER_REFERENCE';
    protected const TEST_SALES_ORDER_ITEM_CONFIGURATION_ARRAY = ['TEST_GROUP_KEY'];

    /**
     * @var \Spryker\Client\SalesProductConfiguration\SalesProductConfigurationClientInterface $salesProductConfigurationClient
     */
    protected $salesProductConfigurationClient;

    /**
     * @var \Spryker\Client\SalesProductConfiguration\SalesProductConfigurationFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $salesProductConfigurationFactoryMock;

    /**
     * @var \SprykerTest\Client\SalesProductConfiguration\SalesProductConfigurationClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->salesProductConfigurationFactoryMock = $this->createSalesProductConfigurationFactoryMock();
        $this->salesProductConfigurationClient = $this->tester->getClient()->setFactory($this->salesProductConfigurationFactoryMock);
    }

    /**
     * @return void
     */
    public function testExpandItemsWithProductConfigurationFromPreviousOrderCheckExpanderSuccess(): void
    {
        //Arrange
        $orderTransfer = (new OrderBuilder())->build()->fromArray([
            OrderTransfer::ORDER_REFERENCE => static::TEST_ORDER_REFERENCE,
            OrderTransfer::CUSTOMER_REFERENCE => static::TEST_CUSTOMER_REFERENCE,
        ]);

        $salesOrderItemConfigurationInstanceMock = $this->getMockBuilder(SalesOrderItemConfigurationTransfer::class)
            ->onlyMethods(['toArray'])
            ->getMock();

        $salesOrderItemConfigurationInstanceMock->method('toArray')->willReturn(static::TEST_SALES_ORDER_ITEM_CONFIGURATION_ARRAY);

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::ID_SALES_ORDER_ITEM => static::TEST_ID_SALES_ORDER_ITEM,
            ItemTransfer::GROUP_KEY => static::TEST_GROUP_KEY,
            ItemTransfer::SALES_ORDER_ITEM_CONFIGURATION => $salesOrderItemConfigurationInstanceMock,
        ]))->build();

        $salesOrderItems = new ArrayObject();
        $salesOrderItems->append($itemTransfer);
        $orderTransfer->setItems($salesOrderItems);

        //Act
        $itemTransferExpandedCollection = $this->tester->getClient()->expandItemsWithProductConfiguration([$itemTransfer], $orderTransfer);
        $isProductConfigurationInstanceComplete = array_shift($itemTransferExpandedCollection)
            ->getProductConfigurationInstance()
            ->getIsComplete();

        //Assert
        $this->assertSame(
            true,
            $isProductConfigurationInstanceComplete,
            'Expects that order items will be successfully expanded with product configuration from a previous order.'
        );
    }

    /**
     * @return \Spryker\Client\SalesProductConfiguration\SalesProductConfigurationFactory
     */
    protected function createSalesProductConfigurationFactoryMock(): SalesProductConfigurationFactory
    {
        $salesProductConfigurationFactoryMock = $this->createMock(SalesProductConfigurationFactory::class);
        $salesProductConfigurationFactoryMock->method('createItemExpander')->willReturn(new ItemExpander());

        return $salesProductConfigurationFactoryMock;
    }
}
