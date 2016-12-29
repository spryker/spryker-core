<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\ProductBundle\Business\Calculation;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use PHPUnit_Framework_MockObject_MockObject;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Calculation\ProductBundlePriceCalculation;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToSalesQueryContainerInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group ProductBundle
 * @group Business
 * @group Calculation
 * @group ProductBundlePriceCalculationTest
 */
class ProductBundlePriceCalculationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testAggregateShouldSumAllBundledItemAmountsToProductBundle()
    {
        $productBundlePriceCalculationMock = $this->createProductPriceCalculationMock();

        $this->setupFindOrderItemsByIdSalesOrder($productBundlePriceCalculationMock);

        $orderTransfer = $this->createOrderTransfer();

        $updatedOrderTransfer = $productBundlePriceCalculationMock->aggregate($orderTransfer);

        $this->assertCount(1, $updatedOrderTransfer->getBundleItems());

        $bundleItems = (array)$updatedOrderTransfer->getBundleItems();
        $bundleItemTransfer = array_pop($bundleItems);

        $this->assertSame(200, $bundleItemTransfer->getUnitGrossPrice());
        $this->assertSame(400, $bundleItemTransfer->getSumGrossPrice());
        $this->assertSame(180, $bundleItemTransfer->getUnitItemTotal());
        $this->assertSame(360, $bundleItemTransfer->getSumItemTotal());
        $this->assertSame(20, $bundleItemTransfer->getFinalUnitDiscountAmount());
        $this->assertSame(40, $bundleItemTransfer->getFinalSumDiscountAmount());

    }

    /**
     * @return void
     */
    public function testCalculateShouldSumAllBundledItemAmountsToProductBundle()
    {
        $productBundlePriceCalculationMock = $this->createProductPriceCalculationMock();

        $quoteTransfer = $this->createQuoteTransfer();

        $bundleItemTransfer = new ItemTransfer();
        $bundleItemTransfer->setBundleItemIdentifier('bundle-identifier');

        $quoteTransfer->addBundleItem($bundleItemTransfer);

        $updatedQuoteTransfer = $productBundlePriceCalculationMock->calculate($quoteTransfer);

        $bundleItems = (array)$updatedQuoteTransfer->getBundleItems();
        $bundleItemTransfer = array_pop($bundleItems);

        $this->assertSame(200, $bundleItemTransfer->getUnitGrossPrice());
        $this->assertSame(400, $bundleItemTransfer->getSumGrossPrice());
        $this->assertSame(180, $bundleItemTransfer->getUnitItemTotal());
        $this->assertSame(360, $bundleItemTransfer->getSumItemTotal());
        $this->assertSame(20, $bundleItemTransfer->getFinalUnitDiscountAmount());
        $this->assertSame(40, $bundleItemTransfer->getFinalSumDiscountAmount());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ProductBundlePriceCalculation
     */
    protected function createProductPriceCalculationMock()
    {
        $queryContainerMock = $this->createSalesQueryContainerMock();

        return $this->getMockBuilder(ProductBundlePriceCalculation::class)
            ->setConstructorArgs([$queryContainerMock])
            ->setMethods(['findOrderItemsByIdSalesOrder'])
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ProductBundleToSalesQueryContainerInterface
     */
    protected function createSalesQueryContainerMock()
    {
        return $this->getMockBuilder(ProductBundleToSalesQueryContainerInterface::class)->getMock();
    }

    /**
     * @param PHPUnit_Framework_MockObject_MockObject $productBundlePriceCalculationMock
     *
     * @return void
     */
    protected function setupFindOrderItemsByIdSalesOrder(
        PHPUnit_Framework_MockObject_MockObject $productBundlePriceCalculationMock
    ) {
        $salesOrderItems = new ObjectCollection();

        $salesOrderItemEntity = new SpySalesOrderItem();
        $salesOrderItemEntity->setIdSalesOrderItem(1);
        $salesOrderItemEntity->setFkSalesOrderItemBundle(1);
        $salesOrderItems->append($salesOrderItemEntity);

        $salesOrderItemEntity = new SpySalesOrderItem();
        $salesOrderItemEntity->setIdSalesOrderItem(2);
        $salesOrderItemEntity->setFkSalesOrderItemBundle(1);
        $salesOrderItems->append($salesOrderItemEntity);

        $productBundlePriceCalculationMock->method('findOrderItemsByIdSalesOrder')
            ->willReturn($salesOrderItems);
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();

        $bundledItems = $this->createBundledItems();
        $orderTransfer->setItems($bundledItems);

        return $orderTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        $quoteTransfer = new QuoteTransfer();

        $bundledItems = $this->createBundledItems();
        $quoteTransfer->setItems($bundledItems);

        return $quoteTransfer;

    }

    /**
     * @return ArrayObject
     */
    protected function createBundledItems()
    {
        $bundledItems = new ArrayObject();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setUnitGrossPrice(100);
        $itemTransfer->setSumGrossPrice(200);
        $itemTransfer->setUnitItemTotal(90);
        $itemTransfer->setSumItemTotal(180);
        $itemTransfer->setFinalUnitDiscountAmount(10);
        $itemTransfer->setFinalSumDiscountAmount(20);
        $itemTransfer->setIdSalesOrderItem(2);
        $itemTransfer->setRelatedBundleItemIdentifier('bundle-identifier');
        $bundledItems->append($itemTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setUnitGrossPrice(100);
        $itemTransfer->setSumGrossPrice(200);
        $itemTransfer->setUnitItemTotal(90);
        $itemTransfer->setSumItemTotal(180);
        $itemTransfer->setFinalUnitDiscountAmount(10);
        $itemTransfer->setFinalSumDiscountAmount(20);
        $itemTransfer->setIdSalesOrderItem(2);
        $itemTransfer->setRelatedBundleItemIdentifier('bundle-identifier');

        $bundledItems->append($itemTransfer);

        return $bundledItems;
    }
}
