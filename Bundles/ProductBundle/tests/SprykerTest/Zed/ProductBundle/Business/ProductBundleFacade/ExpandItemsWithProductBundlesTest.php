<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\ProductBundleFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductBundleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductForBundleTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use SprykerTest\Zed\Sales\Helper\BusinessHelper;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Business
 * @group ProductBundleFacade
 * @group ExpandItemsWithProductBundlesTest
 * Add your own group annotations below this line
 */
class ExpandItemsWithProductBundlesTest extends Unit
{
    protected const STORE_NAME_DE = 'DE';
    protected const CUSTOMER_REFERENCE = 'CUSTOMER_REFERENCE';
    protected const PRODUCT_CONCRETE_SKU_1 = 'PRODUCT_CONCRETE_SKU_1';
    protected const PRODUCT_CONCRETE_SKU_2 = 'PRODUCT_CONCRETE_SKU_2';
    protected const PRODUCT_CONCRETE_SKU_3 = 'PRODUCT_CONCRETE_SKU_3';
    protected const BUNDLE_SKU_1 = 'BUNDLE_SKU_1';
    protected const CURRENCY_ISO_CODE = 'CODE';

    /**
     * @var \SprykerTest\Zed\ProductBundle\ProductBundleBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testExpandBundleItemsWithProductBundles(): void
    {
        // Arrange
        $productConcreteTransfer = $this->createProduct(static::PRODUCT_CONCRETE_SKU_1);
        $bundleTransfer = $this->createProductBundle([$productConcreteTransfer]);
        $orderTransfer = $this->createOrderWithBundleItem($productConcreteTransfer->getSku(), $bundleTransfer->getSku());

        // Act
        $itemTransfers = $this->tester->getFacade()->expandItemsWithProductBundles($orderTransfer->getItems()->getArrayCopy());

        // Assert
        $this->assertNotEmpty($itemTransfers[0]->getProductBundle());
        $this->assertEquals($itemTransfers[0]->getRelatedBundleItemIdentifier(), $itemTransfers[0]->getProductBundle()->getBundleItemIdentifier());
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function createProduct(string $sku): ProductConcreteTransfer
    {
        $productConcreteTransfer = $this->tester->haveProduct([
            ProductConcreteTransfer::SKU => $sku,
            ProductConcreteTransfer::IS_ACTIVE => true,
        ]);

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->tester->haveProductInStockForStore($storeTransfer, [
            StockProductTransfer::SKU => $productConcreteTransfer->getSku(),
            StockProductTransfer::QUANTITY => 10,
            StockProductTransfer::IS_NEVER_OUT_OF_STOCK => true,
        ]);
        $this->tester->haveAvailabilityConcrete($productConcreteTransfer->getSku(), $storeTransfer, 10);

        $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
        ]);

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productsToAssign
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function createProductBundle(array $productsToAssign = []): ProductConcreteTransfer
    {
        $productBundleTransfer = (new ProductBundleTransfer())
            ->setIsNeverOutOfStock(true);

        foreach ($productsToAssign as $productConcreteTransferToAssign) {
            $bundledProductTransfer = new ProductForBundleTransfer();
            $bundledProductTransfer->setQuantity(1);
            $bundledProductTransfer->setIdProductConcrete($productConcreteTransferToAssign->getIdProductConcrete());
            $productBundleTransfer->addBundledProduct($bundledProductTransfer);
        }

        $productConcreteTransfer = $this->createProduct(static::BUNDLE_SKU_1);
        $productConcreteTransfer->setProductBundle($productBundleTransfer);

        $this->tester->getFacade()->saveBundledProducts($productConcreteTransfer);

        return $productConcreteTransfer;
    }

    /**
     * @param string $productConcreteSku
     * @param string $productBundleSku
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderWithBundleItem(string $productConcreteSku, string $productBundleSku): OrderTransfer
    {
        $quoteTransfer = (new QuoteBuilder([QuoteTransfer::CUSTOMER_REFERENCE => static::CUSTOMER_REFERENCE]))
            ->withItem([
                ItemTransfer::SKU => $productConcreteSku,
                ItemTransfer::RELATED_BUNDLE_ITEM_IDENTIFIER => $productBundleSku,
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 1,
            ])
            ->withBundleItem([
                ItemTransfer::SKU => $productBundleSku,
                ItemTransfer::BUNDLE_ITEM_IDENTIFIER => $productBundleSku,
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 1,
            ])
            ->withTotals()
            ->withStore()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCustomer([CustomerTransfer::CUSTOMER_REFERENCE => static::CUSTOMER_REFERENCE])
            ->withCurrency()
            ->build();// ToDo: complete test

        $saveOrderTransfer = $this->tester->haveOrderFromQuote($quoteTransfer, BusinessHelper::DEFAULT_OMS_PROCESS_NAME);

        return (new OrderTransfer())
            ->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder())
            ->setOrderReference($saveOrderTransfer->getOrderReference())
            ->setCustomer($quoteTransfer->getCustomer())
            ->setItems($saveOrderTransfer->getOrderItems());
    }
}
