<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\Sales;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\ProductBundle\Persistence\SpySalesOrderItemBundle;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Sales\ProductBundleSalesOrderSaver;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToSalesQueryContainerInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Business
 * @group Sales
 * @group ProductBundleSalesOrderSaverTest
 * Add your own group annotations below this line
 */
class ProductBundleSalesOrderSaverTest extends Unit
{
    /**
     * @return void
     */
    public function testSaveSaleOrderBundleItemsShouldPersistGivenBundleItems()
    {
        $productBundleMock = $this->createProductBundleSalesOrderSaverMock();

        $salesOrderItemEntity = $this->createSalesOrderItemEntityMock();
        $salesOrderItemEntity->setIdSalesOrderItem(1);

        $productBundleMock->expects($this->once())
            ->method('findSalesOrderItem')
            ->willReturn($salesOrderItemEntity);

        $salesOrderItemBundleEntityMock = $this->createSalesOrderItemBundleEntityMock();
        $salesOrderItemBundleEntityMock->setIdSalesOrderItemBundle(1);
        $productBundleMock->expects($this->once())
            ->method('createSalesOrderItemBundleEntity')
            ->willReturn($salesOrderItemBundleEntityMock);

        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setBundleItemIdentifier('bundle-identifier');

        $quoteTransfer->addBundleItem($itemTransfer);

        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        $saveOrderTransfer = new SaveOrderTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setIdSalesOrderItem(1);
        $itemTransfer->setRelatedBundleItemIdentifier('bundle-identifier');
        $saveOrderTransfer->addOrderItem($itemTransfer);

        $checkoutResponseTransfer->setSaveOrder($saveOrderTransfer);

        $productBundleMock->saveSaleOrderBundleItems($quoteTransfer, $checkoutResponseTransfer);

        $this->assertNotFalse($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Business\ProductBundle\Sales\ProductBundleSalesOrderSaver
     */
    protected function createProductBundleSalesOrderSaverMock()
    {
        $queryContainerMock = $this->createSalesQueryContainerMock();
        $productBundleQueryContainerMock = $this->createProductBundleQueryContainerMock();

        $connectionMock = $this->getMockBuilder(ConnectionInterface::class)->getMock();

        $productBundleQueryContainerMock->method('getConnection')->willReturn($connectionMock);

        return $this->getMockBuilder(ProductBundleSalesOrderSaver::class)
            ->setConstructorArgs([$queryContainerMock, $productBundleQueryContainerMock])
            ->setMethods(['findSalesOrderItem', 'createSalesOrderItemBundleEntity'])
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Orm\Zed\Sales\Persistence\SpySalesOrderItemBundle
     */
    protected function createSalesOrderItemBundleEntityMock()
    {
        $salesOrderItemBundleEntityMock = $this->getMockBuilder(SpySalesOrderItemBundle::class)
           ->setMethods(['save'])
           ->getMock();

        $salesOrderItemBundleEntityMock->expects($this->once())
            ->method('save')
            ->willReturn(1);

        return $salesOrderItemBundleEntityMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    protected function createSalesOrderItemEntityMock()
    {
        $salesOrderItemEntityMock = $this->getMockBuilder(SpySalesOrderItem::class)
            ->setMethods(['save'])
            ->getMock();

        $salesOrderItemEntityMock->expects($this->once())
            ->method('save')
            ->willReturn(1);

        return $salesOrderItemEntityMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToSalesQueryContainerInterface
     */
    protected function createSalesQueryContainerMock()
    {
        return $this->getMockBuilder(ProductBundleToSalesQueryContainerInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface
     */
    protected function createProductBundleQueryContainerMock()
    {
        return $this->getMockBuilder(ProductBundleQueryContainerInterface::class)->getMock();
    }
}
