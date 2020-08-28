<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferSales\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferSales
 * @group Business
 * @group Facade
 * @group ProductOfferSalesFacadeTest
 * Add your own group annotations below this line
 */
class ProductOfferSalesFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductOfferSales\ProductOfferSalesBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandOrderItemWithProductOfferReturnsUpdatedTransferWithCorrectData(): void
    {
        // Arrange
        $productOfferReference = 'test-product-offer-reference';
        $itemTransfer = $this->getItemTransfer([
            ItemTransfer::PRODUCT_OFFER_REFERENCE => $productOfferReference,
        ]);
        $salesOrderItemEntityTransfer = new SpySalesOrderItemEntityTransfer();

        // Act
        $newSalesOrderItemEntityTransfer = $this->tester
            ->getFacade()
            ->expandOrderItemWithProductOffer($salesOrderItemEntityTransfer, $itemTransfer);

        // Assert
        $this->assertEquals($newSalesOrderItemEntityTransfer->getProductOfferReference(), $productOfferReference);
    }

    /**
     * @return void
     */
    public function testExpandOrderItemWithProductOfferDoesNothingWithIncorrectData(): void
    {
        // Arrange
        $itemTransfer = $this->getItemTransfer();
        $salesOrderItemEntityTransfer = new SpySalesOrderItemEntityTransfer();

        // Act
        $newSalesOrderItemEntityTransfer = $this->tester
            ->getFacade()
            ->expandOrderItemWithProductOffer($salesOrderItemEntityTransfer, $itemTransfer);

        // Assert
        $this->assertNull($newSalesOrderItemEntityTransfer->getProductOfferReference());
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\ProductAbstractStorageTransfer
     */
    protected function getItemTransfer(array $seedData = []): ItemTransfer
    {
        return (new ItemBuilder($seedData))->build();
    }
}
