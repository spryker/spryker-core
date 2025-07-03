<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOffer\Communication\Plugin\Cart;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OriginalSalesOrderItemTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\ProductOffer\ProductOfferConfig;
use Spryker\Zed\ProductOffer\Communication\Plugin\Cart\OrderAmendmentFilterInactiveProductOfferPreReloadItemsPlugin;
use SprykerTest\Zed\ProductOffer\ProductOfferCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOffer
 * @group Communication
 * @group Plugin
 * @group Cart
 * @group OrderAmendmentFilterInactiveProductOfferPreReloadItemsPluginTest
 * Add your own group annotations below this line
 */
class OrderAmendmentFilterInactiveProductOfferPreReloadItemsPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductOffer\ProductOfferCommunicationTester
     */
    protected ProductOfferCommunicationTester $tester;

    /**
     * @return void
     */
    public function testPreReloadItemsShouldFilterInactiveProductOfferItems(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE'], false);
        $productTransfer = $this->tester->haveFullProduct();
        $productOfferTransfer1 = $this->haveProductOfferWithStore($productTransfer->getIdProductConcrete(), $storeTransfer);
        $productOfferTransfer2 = $this->haveProductOfferWithStore($productTransfer->getIdProductConcrete(), $storeTransfer, false);
        $productOfferTransfer3 = $this->haveProductOfferWithStore(
            $productTransfer->getIdProductConcrete(),
            $storeTransfer,
            true,
            ProductOfferConfig::STATUS_DENIED,
        );
        $quoteTransfer = (new QuoteTransfer())
            ->addItem((new ItemTransfer())
                ->setSku($productOfferTransfer1->getConcreteSku())
                ->setProductOfferReference($productOfferTransfer1->getProductOfferReference()))
            ->addItem((new ItemTransfer())
                ->setSku($productOfferTransfer2->getConcreteSku())
                ->setProductOfferReference($productOfferTransfer2->getProductOfferReference()))
            ->addItem((new ItemTransfer())
                ->setSku($productOfferTransfer3->getConcreteSku())
                ->setProductOfferReference($productOfferTransfer3->getProductOfferReference()))
            ->setStore((new StoreTransfer())->setName('DE'));

        // Act
        $quoteTransfer = (new OrderAmendmentFilterInactiveProductOfferPreReloadItemsPlugin())->preReloadItems($quoteTransfer);

        // Assert
        $this->assertCount(1, $quoteTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testPreReloadItemsShouldNotFilterInactiveProductOfferItemsFromOriginalOrder(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE'], false);
        $productTransfer = $this->tester->haveFullProduct();
        $productOfferTransfer1 = $this->haveProductOfferWithStore($productTransfer->getIdProductConcrete(), $storeTransfer);
        $productOfferTransfer2 = $this->haveProductOfferWithStore($productTransfer->getIdProductConcrete(), $storeTransfer, false);
        $productOfferTransfer3 = $this->haveProductOfferWithStore(
            $productTransfer->getIdProductConcrete(),
            $storeTransfer,
            true,
            ProductOfferConfig::STATUS_DENIED,
        );
        $quoteTransfer = (new QuoteTransfer())
            ->addItem((new ItemTransfer())
                ->setSku($productOfferTransfer1->getConcreteSku())
                ->setProductOfferReference($productOfferTransfer1->getProductOfferReference()))
            ->addItem((new ItemTransfer())
                ->setSku($productOfferTransfer2->getConcreteSku())
                ->setProductOfferReference($productOfferTransfer2->getProductOfferReference()))
            ->addItem((new ItemTransfer())
                ->setSku($productOfferTransfer3->getConcreteSku())
                ->setProductOfferReference($productOfferTransfer3->getProductOfferReference()))
            ->setStore((new StoreTransfer())->setName('DE'));

        $quoteTransfer->setOriginalSalesOrderItems(new ArrayObject([
            (new OriginalSalesOrderItemTransfer())
                ->setSku($productOfferTransfer1->getConcreteSku())
                ->setProductOfferReference($productOfferTransfer1->getProductOfferReference()),
            (new OriginalSalesOrderItemTransfer())
                ->setSku($productOfferTransfer2->getConcreteSku())
                ->setProductOfferReference($productOfferTransfer2->getProductOfferReference()),
            (new OriginalSalesOrderItemTransfer())
                ->setSku($productOfferTransfer3->getConcreteSku())
                ->setProductOfferReference($productOfferTransfer3->getProductOfferReference()),
        ]));

        // Act
        $quoteTransfer = (new OrderAmendmentFilterInactiveProductOfferPreReloadItemsPlugin())->preReloadItems($quoteTransfer);

        // Assert
        $this->assertCount(3, $quoteTransfer->getItems());
    }

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param bool $isActive
     * @param string $approvalStatus
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected function haveProductOfferWithStore(
        int $idProductConcrete,
        StoreTransfer $storeTransfer,
        bool $isActive = true,
        string $approvalStatus = ProductOfferConfig::STATUS_APPROVED
    ): ProductOfferTransfer {
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::ID_PRODUCT_CONCRETE => $idProductConcrete,
            ProductOfferTransfer::IS_ACTIVE => $isActive,
            ProductOfferTransfer::APPROVAL_STATUS => $approvalStatus,
        ]);
        $this->tester->haveProductOfferStore($productOfferTransfer, $storeTransfer);

        return $productOfferTransfer;
    }
}
