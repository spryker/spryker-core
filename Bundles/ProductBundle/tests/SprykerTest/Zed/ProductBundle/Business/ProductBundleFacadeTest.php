<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductBundleCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductBundleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductForBundleTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundleQuery;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Cache\ProductBundleCache;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartItemGroupKeyExpander;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityFacadeBridge;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Business
 * @group Facade
 * @group ProductBundleFacadeTest
 * Add your own group annotations below this line
 */
class ProductBundleFacadeTest extends Unit
{
    public const SKU_BUNDLED_1 = 'sku-1-test-tester';
    public const SKU_BUNDLED_2 = 'sku-2-test-tester';
    public const BUNDLE_SKU_3 = 'sku-3-test-tester';

    public const BUNDLED_PRODUCT_PRICE_1 = 50;
    public const BUNDLED_PRODUCT_PRICE_2 = 100;
    public const ID_STORE = 1;
    protected const STORE_NAME_DE = 'DE';

    protected const FAKE_ID_PRODUCT_CONCRETE = 6666;

    /**
     * @var \SprykerTest\Zed\ProductBundle\ProductBundleBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->cleanProductBundleCache();
    }

    /**
     * @return void
     */
    public function testExpandBundleItemsShouldCreateBundleItemsAndCalculateSplitPrice(): void
    {
        $this->markTestSkipped();
        // Arrange
        $bundlePrice = static::BUNDLED_PRODUCT_PRICE_2;

        $productConcreteTransfer = $this->createProductBundle($bundlePrice);

        $cartChangeTransfer = new CartChangeTransfer();
        $currencyTransfer = new CurrencyTransfer();
        $currencyTransfer->setCode('EUR');
        $quoteTransfer = $this->createBaseQuoteTransfer();
        $quoteTransfer->setCurrency($currencyTransfer);
        $cartChangeTransfer->setQuote($quoteTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity(static::ID_STORE);
        $itemTransfer->setUnitGrossPrice($productConcreteTransfer->getPrices()[0]->getPrice());
        $itemTransfer->setId($productConcreteTransfer->getIdProductConcrete());
        $itemTransfer->setSku($productConcreteTransfer->getSku());

        $cartChangeTransfer->addItem($itemTransfer);

        // Act
        $cartChangeTransfer = $this->getProductBundleFacade()
            ->expandBundleItems($cartChangeTransfer);

        $quoteTransfer = $cartChangeTransfer->getQuote();

        $this->assertCount(3, $cartChangeTransfer->getItems());
        $this->assertCount(static::ID_STORE, $quoteTransfer->getBundleItems());

        $bundledItemTransfer = $quoteTransfer->getBundleItems()[0];
        $bundleItemIdentifier = $bundledItemTransfer->getBundleItemIdentifier();

        $this->assertSame($bundlePrice, $bundledItemTransfer->getUnitGrossPrice());

        $itemTransfer = $cartChangeTransfer->getItems()[0];
        $this->assertSame(20, $itemTransfer->getUnitGrossPrice());
        $this->assertSame($bundleItemIdentifier, $itemTransfer->getRelatedBundleItemIdentifier());

        $itemTransfer = $cartChangeTransfer->getItems()[static::ID_STORE];
        $itemTransfer->getUnitGrossPrice();
        $this->assertSame(40, $itemTransfer->getUnitGrossPrice());
        $this->assertSame($bundleItemIdentifier, $itemTransfer->getRelatedBundleItemIdentifier());

        $itemTransfer = $cartChangeTransfer->getItems()[2];
        $itemTransfer->getUnitGrossPrice();
        $this->assertSame(40, $itemTransfer->getUnitGrossPrice());
        $this->assertSame($bundleItemIdentifier, $itemTransfer->getRelatedBundleItemIdentifier());
    }

    /**
     * @return void
     */
    public function testGetProductBundleCollectionByCriteriaFilterShouldReturnPersistedBundledProducts(): void
    {
        //Assign
        $productConcreteBundleTransfer = $this->createProductBundle(static::BUNDLED_PRODUCT_PRICE_2);

        foreach ($productConcreteBundleTransfer->getProductBundle()->getBundledProducts() as $bundledProduct) {
            //Act
            $productBundleCollection = $this->getProductBundleFacade()->getProductBundleCollectionByCriteriaFilter(
                (new ProductBundleCriteriaFilterTransfer())->setIdBundledProduct($bundledProduct->getIdProductConcrete())
            );

            /** @var \Generated\Shared\Transfer\ProductBundleTransfer $productBundleTransfer */
            $productBundleTransfer = $productBundleCollection->getProductBundles()->offsetGet(0);
            $bundleProduct = SpyProductBundleQuery::create()->findOneByFkBundledProduct($bundledProduct->getIdProductConcrete());

            //Assert
            $this->assertSame(1, $productBundleCollection->getProductBundles()->count());
            $this->assertSame($bundleProduct->getFkProduct(), $productBundleTransfer->getIdProductConcreteBundle());
        }
    }

    /**
     * @return void
     */
    public function testGetProductBundleCollectionByCriteriaFilterWithProductConcreteIdFilter(): void
    {
        //Assign
        $productConcreteBundleTransfer = $this->createProductBundle(static::BUNDLED_PRODUCT_PRICE_1);

        $productBundleCriteriaFilterTransfer = (new ProductBundleCriteriaFilterTransfer())
            ->addIdProductConcrete($productConcreteBundleTransfer->getIdProductConcrete());

        //Act
        $productBundleTransfers = $this->getProductBundleFacade()
            ->getProductBundleCollectionByCriteriaFilter($productBundleCriteriaFilterTransfer)
            ->getProductBundles();

        //Assert
        $this->assertCount(2, $productBundleTransfers);
        $this->assertEquals($productBundleTransfers->offsetGet(0), $productBundleTransfers->offsetGet(1));
    }

    /**
     * @return void
     */
    public function testGetProductBundleCollectionByCriteriaFilterWithFakeProductConcreteIdFilter(): void
    {
        //Assign
        $productBundleCriteriaFilterTransfer = (new ProductBundleCriteriaFilterTransfer())
            ->addIdProductConcrete(static::FAKE_ID_PRODUCT_CONCRETE);

        //Act
        $productBundleTransfers = $this->getProductBundleFacade()
            ->getProductBundleCollectionByCriteriaFilter($productBundleCriteriaFilterTransfer)
            ->getProductBundles();

        //Assert
        $this->assertEmpty($productBundleTransfers);
    }

    /**
     * @return void
     */
    public function testExpandCartItemGroupKeyShouldAppendBundleToKey(): void
    {
        // Arrange
        $cartChangeTransfer = new CartChangeTransfer();

        $groupKeyBefore = 'test1';
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setGroupKey($groupKeyBefore);
        $itemTransfer->setRelatedBundleItemIdentifier('related1');

        $cartChangeTransfer->addItem($itemTransfer);

        // Act
        $cartChangeTransfer = $this->getProductBundleFacade()
            ->expandBundleCartItemGroupKey($cartChangeTransfer);

        $itemTransfer = $cartChangeTransfer->getItems()[0];

        $this->assertEquals(
            $groupKeyBefore . ProductBundleCartItemGroupKeyExpander::GROUP_KEY_DELIMITER . $itemTransfer->getRelatedBundleItemIdentifier() . '1',
            $itemTransfer->getGroupKey()
        );
    }

    /**
     * @return void
     */
    public function testPostSaveCartUpdateBundlesShouldReturnQuoteWithoutBundlesWhenBundleIsRemoved(): void
    {
        // Arrange
        $quoteTransfer = $this->createBaseQuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setBundleItemIdentifier('bundleid');

        $quoteTransfer->addBundleItem($itemTransfer);

        // Act
        $quoteTransfer = $this->getProductBundleFacade()
            ->postSaveCartUpdateBundles($quoteTransfer);

        $this->assertCount(0, $quoteTransfer->getBundleItems());
    }

    /**
     * @return void
     */
    public function testPreCheckCartActiveWhenBundleProductIsActive(): void
    {
        // Arrange
        $productConcreteTransfer = $this->createProductBundle(static::BUNDLED_PRODUCT_PRICE_2, true);

        $cartChangeTransfer = new CartChangeTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku($productConcreteTransfer->getSku());

        $cartChangeTransfer->addItem($itemTransfer);

        // Act
        $cartChangeTransfer = $this->getProductBundleFacade()
            ->preCheckCartActive($cartChangeTransfer);

        $this->assertTrue($cartChangeTransfer->getIsSuccess());
        $this->assertEmpty($cartChangeTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testPreCheckCartActiveWhenBundleProductIsNotActive(): void
    {
        // Arrange
        $productConcreteTransfer = $this->createProductBundle(static::BUNDLED_PRODUCT_PRICE_2, false);

        $cartChangeTransfer = new CartChangeTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku($productConcreteTransfer->getSku());

        $cartChangeTransfer->addItem($itemTransfer);

        // Act
        $cartChangeTransfer = $this->getProductBundleFacade()
            ->preCheckCartActive($cartChangeTransfer);

        // Assert
        $this->assertFalse($cartChangeTransfer->getIsSuccess());
        $this->assertNotEmpty($cartChangeTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testPreCheckCartAvailabilityWhenBundleAvailable(): void
    {
        // Arrange
        $productConcreteTransfer = $this->createProductBundle(static::BUNDLED_PRODUCT_PRICE_2, true);

        $cartChangeTransfer = new CartChangeTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku($productConcreteTransfer->getSku());

        $quoteTransfer = $this->createBaseQuoteTransfer();
        $quoteTransfer->addItem($itemTransfer);

        $cartChangeTransfer->setQuote($quoteTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku($productConcreteTransfer->getSku());
        $itemTransfer->setQuantity(static::ID_STORE);

        $cartChangeTransfer->addItem($itemTransfer);

        // Act
        $cartChangeTransfer = $this->getProductBundleFacade()
            ->preCheckCartAvailability($cartChangeTransfer);

        $this->assertTrue($cartChangeTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testPreCheckCartAvailabilityWhenBundleUnavailable(): void
    {
        // Arrange
        $productConcreteTransfer = $this->createProductBundle(static::BUNDLED_PRODUCT_PRICE_2);

        $cartChangeTransfer = new CartChangeTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity(5);
        $itemTransfer->setSku($productConcreteTransfer->getSku());

        $quoteTransfer = $this->createBaseQuoteTransfer();
        $quoteTransfer->addItem($itemTransfer);

        $cartChangeTransfer->setQuote($quoteTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku($productConcreteTransfer->getSku());
        $itemTransfer->setQuantity(25);

        $cartChangeTransfer->addItem($itemTransfer);

        // Act
        $cartChangeTransfer = $this->getProductBundleFacade()->preCheckCartAvailability($cartChangeTransfer);

        // Assert
        $this->assertFalse($cartChangeTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testPreCheckCheckoutAvailabilityWhenBundleUnavailable(): void
    {
        // Arrange
        $productConcreteTransfer = $this->createProductBundle(static::BUNDLED_PRODUCT_PRICE_2);

        $quoteTransfer = $this->createBaseQuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity(50);
        $itemTransfer->setSku(static::SKU_BUNDLED_1);

        $quoteTransfer->addItem($itemTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity(static::ID_STORE);
        $itemTransfer->setSku($productConcreteTransfer->getSku());
        $quoteTransfer->addBundleItem($itemTransfer);

        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $this->getProductBundleFacade()->preCheckCheckoutAvailability($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testPreCheckCheckoutAvailabilityWhenBundleAvailable(): void
    {
        // Arrange
        $productConcreteTransfer = $this->createProductBundle(static::BUNDLED_PRODUCT_PRICE_2, true);

        $quoteTransfer = $this->createBaseQuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity(static::ID_STORE);
        $itemTransfer->setSku(static::SKU_BUNDLED_1);

        $quoteTransfer->addItem($itemTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity(static::ID_STORE);
        $itemTransfer->setSku($productConcreteTransfer->getSku());
        $quoteTransfer->addBundleItem($itemTransfer);

        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $this->getProductBundleFacade()->preCheckCheckoutAvailability($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertNull($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUpdateAffectedBundleAvailabilityWhenOneOfBundledItemsUnavailable(): void
    {
        // Arrange
        $productConcreteTransferToAssign1 = $this->createProduct(static::BUNDLED_PRODUCT_PRICE_1, static::SKU_BUNDLED_1);
        $productConcreteTransferToAssign2 = $this->createProduct(static::BUNDLED_PRODUCT_PRICE_2, static::SKU_BUNDLED_2);

        $this->createProductBundle(
            static::BUNDLED_PRODUCT_PRICE_2,
            false,
            [
                $productConcreteTransferToAssign1,
                $productConcreteTransferToAssign2,
            ]
        );

        $storeTransfer = (new StoreTransfer())->setIdStore(static::ID_STORE);

        $this->tester->haveAvailabilityAbstract($productConcreteTransferToAssign1, new Decimal(0));

        // Act
        $this->getProductBundleFacade()
            ->updateAffectedBundlesAvailability($productConcreteTransferToAssign2->getSku());

        $bundledProductAvailability = $this->createAvailabilityFacade()
            ->findOrCreateProductConcreteAvailabilityBySkuForStore(
                static::BUNDLE_SKU_3,
                $storeTransfer
            );

        // Assert
        $this->assertEquals(0, $bundledProductAvailability->getAvailability()->toString());
    }

    /**
     * @return void
     */
    public function testSaveBundledProductsShouldAddProvidedConcreteToBundle(): void
    {
        // Arrange
        $productConcreteBundleTransfer = $this->createProduct(static::BUNDLED_PRODUCT_PRICE_1, static::BUNDLE_SKU_3);
        $productConcreteToAssignTransfer = $this->createProduct(static::BUNDLED_PRODUCT_PRICE_1, static::SKU_BUNDLED_1);

        $productBundleTransfer = new ProductBundleTransfer();

        $bundledProductTransfer = new ProductForBundleTransfer();
        $bundledProductTransfer->setIdProductConcrete($productConcreteToAssignTransfer->getIdProductConcrete());
        $bundledProductTransfer->setIdProductBundle($productConcreteBundleTransfer->getIdProductConcrete());
        $bundledProductTransfer->setQuantity(2);

        $productBundleTransfer->addBundledProduct($bundledProductTransfer);

        $productConcreteBundleTransfer->setProductBundle($productBundleTransfer);

        // Act
        $productConcreteBundleTransfer = $this->getProductBundleFacade()
            ->saveBundledProducts($productConcreteBundleTransfer);

        $bundledProducts = SpyProductBundleQuery::create()->findByFkProduct($productConcreteBundleTransfer->getIdProductConcrete());

        $this->assertCount(static::ID_STORE, $bundledProducts);

        $bundledProductEntity = $bundledProducts[0];

        $this->assertSame($bundledProductTransfer->getQuantity(), $bundledProductEntity->getQuantity());
        $this->assertSame($productConcreteToAssignTransfer->getIdProductConcrete(), $bundledProductEntity->getFkBundledProduct());
        $this->assertSame($productConcreteBundleTransfer->getIdProductConcrete(), $bundledProductEntity->getFkProduct());
    }

    /**
     * @return void
     */
    public function testSaveBundledProductsWhenRemoveListProvidedShouldRemoveBundledProducts(): void
    {
        $this->markTestIncomplete('Something with transactions');
        // Arrange
        $productConcreteBundleTransfer = $this->createProductBundle(static::BUNDLED_PRODUCT_PRICE_2);

        $productBundleTransfer = $productConcreteBundleTransfer->getProductBundle();
        $bundledProducts = $productBundleTransfer->getBundledProducts();
        $productBundleTransfer->setBundlesToRemove([$bundledProducts[0]->getIdProductConcrete()]);
        $productConcreteBundleTransfer->setProductBundle($productBundleTransfer);

        // Act
        $productConcreteBundleTransfer = $this->getProductBundleFacade()
            ->saveBundledProducts($productConcreteBundleTransfer);

        $bundledProducts = SpyProductBundleQuery::create()->findByFkProduct($productConcreteBundleTransfer->getIdProductConcrete());

        $this->assertCount(static::ID_STORE, $bundledProducts);
    }

    /**
     * @return void
     */
    public function testFindBundledProductsByIdProductConcreteShouldReturnPersistedBundledProducts(): void
    {
        $this->markTestIncomplete('Something with transactions');
        // Arrange
        $productConcreteBundleTransfer = $this->createProductBundle(static::BUNDLED_PRODUCT_PRICE_2);

        // Act
        $bundledProducts = $this->getProductBundleFacade()
            ->findBundledProductsByIdProductConcrete(
                $productConcreteBundleTransfer->getIdProductConcrete()
            );

        $this->assertCount(2, $bundledProducts);
    }

    /**
     * @return void
     */
    public function testAssignBundledProductsToProductConcreteShouldAssignPersistedBundledProducts(): void
    {
        $this->markTestIncomplete('Something with transactions');
        // Arrange
        $productConcreteBundleTransfer = $this->createProductBundle(static::BUNDLED_PRODUCT_PRICE_2);

        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setIdProductConcrete($productConcreteBundleTransfer->getIdProductConcrete());

        // Act
        $productConcreteTransfer = $this->getProductBundleFacade()
            ->assignBundledProductsToProductConcrete($productConcreteTransfer);

        $this->assertNotNull($productConcreteTransfer->getProductBundle());
        $this->assertCount(2, $productConcreteTransfer->getProductBundle()->getBundledProducts());
    }

    /**
     * @return void
     */
    public function testFilterBundleItemsOnCartReloadShouldRemoveBundleItems(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withBundleItem([
                ItemTransfer::BUNDLE_ITEM_IDENTIFIER => static::ID_STORE,
            ])->withItem([
                ItemTransfer::RELATED_BUNDLE_ITEM_IDENTIFIER => static::ID_STORE,
            ])->withAnotherItem([
                ItemTransfer::SKU => '123',
            ])
            ->build();

        // Act
        $updatedQuoteTransfer = $this->getProductBundleFacade()
            ->filterBundleItemsOnCartReload($quoteTransfer);

        $this->assertCount(0, $updatedQuoteTransfer->getBundleItems());
        $this->assertCount(2, $updatedQuoteTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testDeactivateRelatedProductBundlesWithInactiveProductConcrete(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->createProductBundle(static::BUNDLED_PRODUCT_PRICE_2, false);

        // Act
        $productConcreteTransfer = $this->getProductBundleFacade()
            ->deactivateRelatedProductBundles($productConcreteTransfer);

        // Assert
        $this->assertFalse($productConcreteTransfer->getIsActive());
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface
     */
    protected function getProductBundleFacade()
    {
        return $this->tester->getFacade();
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityFacadeInterface
     */
    protected function createAvailabilityFacade(): ProductBundleToAvailabilityFacadeInterface
    {
        return new ProductBundleToAvailabilityFacadeBridge($this->tester->getLocator()->availability()->facade());
    }

    /**
     * @param int $price
     * @param string $sku
     * @param bool $isAlwaysAvailable
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function createProduct(int $price, string $sku, bool $isAlwaysAvailable = false): ProductConcreteTransfer
    {
        $currencyTransfer = $this->tester->haveCurrencyTransfer([CurrencyTransfer::CODE => 'EUR']);
        $productConcreteTransfer = $this->tester->haveProduct([
            ProductConcreteTransfer::SKU => $sku,
            ProductConcreteTransfer::IS_ACTIVE => $isAlwaysAvailable,
        ]);

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->tester->haveProductInStockForStore($storeTransfer, [
            StockProductTransfer::SKU => $productConcreteTransfer->getSku(),
            StockProductTransfer::QUANTITY => 10,
            StockProductTransfer::IS_NEVER_OUT_OF_STOCK => $isAlwaysAvailable,
        ]);
        $this->tester->haveAvailabilityConcrete($productConcreteTransfer->getSku(), $storeTransfer, 10);

        $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => $price,
                MoneyValueTransfer::GROSS_AMOUNT => $price,
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);

        return $productConcreteTransfer;
    }

    /**
     * @param int $bundlePrice
     * @param bool $isAlwaysAvailable
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productsToAssign
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function createProductBundle($bundlePrice, $isAlwaysAvailable = false, array $productsToAssign = []): ProductConcreteTransfer
    {
        if ($productsToAssign === []) {
            $productsToAssign = [
                $this->createProduct(static::BUNDLED_PRODUCT_PRICE_1, static::SKU_BUNDLED_1, $isAlwaysAvailable),
                $this->createProduct(static::BUNDLED_PRODUCT_PRICE_2, static::SKU_BUNDLED_2, $isAlwaysAvailable),
            ];
        }

        $productBundleTransfer = new ProductBundleTransfer();
        if ($isAlwaysAvailable) {
            $productBundleTransfer->setIsNeverOutOfStock(true);
        }

        foreach ($productsToAssign as $productConcreteTransferToAssign) {
            $bundledProductTransfer = new ProductForBundleTransfer();
            $bundledProductTransfer->setQuantity(1);
            $bundledProductTransfer->setIdProductConcrete($productConcreteTransferToAssign->getIdProductConcrete());
            $productBundleTransfer->addBundledProduct($bundledProductTransfer);
        }

        $productConcreteTransfer = $this->createProduct($bundlePrice, static::BUNDLE_SKU_3, $isAlwaysAvailable);
        $productConcreteTransfer->setProductBundle($productBundleTransfer);

        $this->getProductBundleFacade()->saveBundledProducts($productConcreteTransfer);

        return $productConcreteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createBaseQuoteTransfer(): QuoteTransfer
    {
        $storeTransfer = (new StoreTransfer())->setName(static::STORE_NAME_DE);

        return (new QuoteTransfer())
            ->setStore($storeTransfer);
    }

    /**
     * @return void
     */
    protected function cleanProductBundleCache(): void
    {
        (new ProductBundleCache())->cleanCache();
    }
}
