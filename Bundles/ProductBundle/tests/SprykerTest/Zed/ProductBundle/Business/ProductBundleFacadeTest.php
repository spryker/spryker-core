<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductBundleCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductBundleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductForBundleTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundleQuery;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartItemGroupKeyExpander;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityFacadeBridge;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityFacadeInterface;
use SprykerTest\Zed\ProductBundle\ProductBundleBusinessTester;

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
    /**
     * @var int
     */
    protected const ID_STORE = 1;

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_GROSS
     *
     * @var string
     */
    protected const GROSS_MODE = 'GROSS_MODE';

    /**
     * @var \SprykerTest\Zed\ProductBundle\ProductBundleBusinessTester
     */
    protected ProductBundleBusinessTester $tester;

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->tester->cleanProductBundleCache();
    }

    /**
     * @return void
     */
    public function testExpandBundleItemsShouldCreateBundleItemsAndCalculateSplitPrice(): void
    {
        $this->markTestSkipped();
        // Arrange
        $bundlePrice = ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_2;

        $productConcreteTransfer = $this->tester->createProductBundle($bundlePrice);

        $cartChangeTransfer = new CartChangeTransfer();
        $currencyTransfer = new CurrencyTransfer();
        $currencyTransfer->setCode('EUR');
        $quoteTransfer = $this->tester->createBaseQuoteTransfer();
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
        $productConcreteBundleTransfer = $this->tester->createProductBundle(ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_2);

        foreach ($productConcreteBundleTransfer->getProductBundle()->getBundledProducts() as $bundledProduct) {
            //Act
            $productBundleCollection = $this->getProductBundleFacade()->getProductBundleCollectionByCriteriaFilter(
                (new ProductBundleCriteriaFilterTransfer())->setIdBundledProduct($bundledProduct->getIdProductConcrete()),
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

        $this->assertSame(
            $groupKeyBefore . ProductBundleCartItemGroupKeyExpander::GROUP_KEY_DELIMITER . $itemTransfer->getRelatedBundleItemIdentifier() . '1',
            $itemTransfer->getGroupKey(),
        );
    }

    /**
     * @return void
     */
    public function testPostSaveCartUpdateBundlesShouldReturnQuoteWithoutBundlesWhenBundleIsRemoved(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createBaseQuoteTransfer();

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
        $productConcreteTransfer = $this->tester->createProductBundle(ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_2, true, true);

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
        $productConcreteTransfer = $this->tester->createProductBundle(ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_2);

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
        $productConcreteTransfer = $this->tester->createProductBundle(ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_2, true, true);

        $cartChangeTransfer = new CartChangeTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku($productConcreteTransfer->getSku());

        $quoteTransfer = $this->tester->createBaseQuoteTransfer();
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
        $productConcreteTransfer = $this->tester->createProductBundle(ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_2);

        $cartChangeTransfer = new CartChangeTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity(5);
        $itemTransfer->setSku($productConcreteTransfer->getSku());

        $quoteTransfer = $this->tester->createBaseQuoteTransfer();
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
        $productConcreteTransfer = $this->tester->createProductBundle(ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_2);

        $quoteTransfer = $this->tester->createBaseQuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity(50);
        $itemTransfer->setSku(ProductBundleBusinessTester::SKU_BUNDLED_1);

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
        $productConcreteTransfer = $this->tester->createProductBundle(ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_2, true, true);

        $quoteTransfer = $this->tester->createBaseQuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity(static::ID_STORE);
        $itemTransfer->setSku(ProductBundleBusinessTester::SKU_BUNDLED_1);

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
        $productConcreteTransferToAssign1 = $this->tester->createProduct(
            ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_1,
            ProductBundleBusinessTester::SKU_BUNDLED_1,
            true,
        );
        $productConcreteTransferToAssign2 = $this->tester->createProduct(
            ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_2,
            ProductBundleBusinessTester::SKU_BUNDLED_2,
        );

        $this->tester->createProductBundle(
            ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_2,
            false,
            false,
            [
                $productConcreteTransferToAssign1,
                $productConcreteTransferToAssign2,
            ],
        );

        $storeTransfer = (new StoreTransfer())->setIdStore(static::ID_STORE);

        $this->tester->haveAvailabilityAbstract($productConcreteTransferToAssign1, new Decimal(0), static::ID_STORE);

        // Act
        $this->getProductBundleFacade()
            ->updateAffectedBundlesAvailability($productConcreteTransferToAssign2->getSku());

        $bundledProductAvailability = $this->createAvailabilityFacade()
            ->findOrCreateProductConcreteAvailabilityBySkuForStore(
                ProductBundleBusinessTester::BUNDLE_SKU_3,
                $storeTransfer,
            );

        // Assert
        $this->assertSame('0.0000000000', $bundledProductAvailability->getAvailability()->toString());
    }

    /**
     * @return void
     */
    public function testUpdateAffectedBundleAvailabilityShouldMakeProductBundleSellableWhenAllBundleProductsAreActiveAndHaveStock(): void
    {
        // Arrange
        $productConcreteTransferToAssign1 = $this->tester->createProduct(
            ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_1,
            ProductBundleBusinessTester::SKU_BUNDLED_1,
            true,
        );
        $productConcreteTransferToAssign2 = $this->tester->createProduct(
            ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_2,
            ProductBundleBusinessTester::SKU_BUNDLED_2,
            true,
        );

        $this->tester->createProductBundle(
            ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_2,
            false,
            false,
            [
                $productConcreteTransferToAssign1,
                $productConcreteTransferToAssign2,
            ],
            ProductBundleBusinessTester::BUNDLE_SKU_3,
        );

        // Act
        $this->getProductBundleFacade()->updateAffectedBundlesAvailability($productConcreteTransferToAssign2->getSku());

        $isSellable = $this->createAvailabilityFacade()->isProductSellableForStore(
            ProductBundleBusinessTester::BUNDLE_SKU_3,
            new Decimal(1),
            (new StoreTransfer())->setIdStore(static::ID_STORE),
        );

        // Assert
        $this->assertTrue($isSellable);
    }

    /**
     * @return void
     */
    public function testUpdateAffectedBundleAvailabilityShouldCalculateCorrectlyWhenOneOfProductsHasNoStock(): void
    {
        // Arrange
        $productConcreteTransferToAssign1 = $this->tester->createProduct(
            ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_1,
            ProductBundleBusinessTester::SKU_BUNDLED_1,
            true,
        );
        $productConcreteTransferToAssign2 = $this->tester->createProduct(
            ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_2,
            ProductBundleBusinessTester::SKU_BUNDLED_2,
            true,
            false,
            0,
        );

        $this->tester->createProductBundle(
            ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_2,
            false,
            false,
            [
                $productConcreteTransferToAssign1,
                $productConcreteTransferToAssign2,
            ],
            ProductBundleBusinessTester::BUNDLE_SKU_3,
        );

        // Act
        $this->getProductBundleFacade()->updateAffectedBundlesAvailability($productConcreteTransferToAssign2->getSku());

        $isSellable = $this->createAvailabilityFacade()->isProductSellableForStore(
            ProductBundleBusinessTester::BUNDLE_SKU_3,
            new Decimal(1),
            (new StoreTransfer())->setIdStore(static::ID_STORE),
        );

        // Assert
        $this->assertFalse($isSellable);
    }

    /**
     * @return void
     */
    public function testSaveBundledProductsShouldAddProvidedConcreteToBundle(): void
    {
        // Arrange
        $productConcreteBundleTransfer = $this->tester->createProduct(ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_1, ProductBundleBusinessTester::BUNDLE_SKU_3);
        $productConcreteToAssignTransfer = $this->tester->createProduct(ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_1, ProductBundleBusinessTester::SKU_BUNDLED_1);

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
        $productConcreteBundleTransfer = $this->tester->createProductBundle(ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_2);

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
        $productConcreteBundleTransfer = $this->tester->createProductBundle(ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_2);

        // Act
        $bundledProducts = $this->getProductBundleFacade()
            ->findBundledProductsByIdProductConcrete(
                $productConcreteBundleTransfer->getIdProductConcrete(),
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
        $productConcreteBundleTransfer = $this->tester->createProductBundle(ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_2);

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
    public function testExpandProductConcreteTransfersWithBundledProductsSuccessful(): void
    {
        // Arrange
        $productConcreteBundleTransfer = $this->tester->createProductBundle(ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_2);

        $productConcreteTransferWithBundle = new ProductConcreteTransfer();
        $productConcreteTransferWithBundle->setIdProductConcrete($productConcreteBundleTransfer->getIdProductConcrete());
        $productConcreteTransferWithBundle->setSku($productConcreteBundleTransfer->getSkuOrFail());

        $productConcreteTransferWithoutBundle = new ProductConcreteTransfer();
        $productConcreteTransferWithoutBundle->setIdProductConcrete(
            $productConcreteBundleTransfer->getProductBundle()->getBundledProducts()[1]->getIdProductConcreteOrFail(),
        );
        $productConcreteTransferWithoutBundle->setSku('unknown');

        // Act
        $productConcreteTransfers = $this->getProductBundleFacade()
            ->expandProductConcreteTransfersWithBundledProducts(
                [$productConcreteTransferWithBundle, $productConcreteTransferWithoutBundle],
            );

        $this->assertNotNull($productConcreteTransfers[0]->getProductBundle());
        $this->assertCount(2, $productConcreteTransfers[0]->getProductBundle()->getBundledProducts());

        $this->assertNull($productConcreteTransfers[1]->getProductBundle());
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
        $productConcreteTransfer = $this->tester->createProductBundle(ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_2);

        // Act
        $productConcreteTransfer = $this->getProductBundleFacade()
            ->deactivateRelatedProductBundles($productConcreteTransfer);

        // Assert
        $this->assertFalse($productConcreteTransfer->getIsActive());
    }

    /**
     * @return void
     */
    public function testCalculateBundlePriceForCalculableObjectTransferShouldCalculateBundlePricesByRelatedItems(): void
    {
        // Arrange
        $bundleItemTransfers = new ArrayObject([
            (new ItemTransfer())
                ->setBundleItemIdentifier(ProductBundleBusinessTester::FAKE_BUNDLE_ITEM_IDENTIFIER_1)
                ->setUnitPrice(1000)
                ->setSumPrice(1500)
                ->setUnitNetPrice(500)
                ->setSumNetPrice(550)
                ->setUnitGrossPrice(600)
                ->setSumGrossPrice(650)
                ->setUnitSubtotalAggregation(200)
                ->setSumSubtotalAggregation(250)
                ->setUnitDiscountAmountFullAggregation(400)
                ->setSumDiscountAmountFullAggregation(450)
                ->setUnitDiscountAmountAggregation(100)
                ->setSumDiscountAmountAggregation(150)
                ->setUnitPriceToPayAggregation(200)
                ->setSumPriceToPayAggregation(250),
            (new ItemTransfer())
                ->setBundleItemIdentifier(ProductBundleBusinessTester::FAKE_BUNDLE_ITEM_IDENTIFIER_2)
                ->setUnitPrice(2000)
                ->setSumPrice(2500)
                ->setUnitNetPrice(1500)
                ->setSumNetPrice(1550)
                ->setUnitGrossPrice(1600)
                ->setSumGrossPrice(1650)
                ->setUnitSubtotalAggregation(1200)
                ->setSumSubtotalAggregation(1250)
                ->setUnitDiscountAmountFullAggregation(1400)
                ->setSumDiscountAmountFullAggregation(1450)
                ->setUnitDiscountAmountAggregation(1100)
                ->setSumDiscountAmountAggregation(1150)
                ->setUnitPriceToPayAggregation(1200)
                ->setSumPriceToPayAggregation(1250),
        ]);

        $itemTransfers = new ArrayObject([
            (new ItemTransfer())
                ->setRelatedBundleItemIdentifier(ProductBundleBusinessTester::FAKE_BUNDLE_ITEM_IDENTIFIER_1)
                ->setUnitPrice(100)
                ->setSumPrice(150)
                ->setUnitNetPrice(200)
                ->setSumNetPrice(250)
                ->setUnitGrossPrice(100)
                ->setSumGrossPrice(150)
                ->setUnitSubtotalAggregation(100)
                ->setSumSubtotalAggregation(200)
                ->setUnitDiscountAmountFullAggregation(150)
                ->setSumDiscountAmountFullAggregation(250)
                ->setUnitDiscountAmountAggregation(200)
                ->setSumDiscountAmountAggregation(100)
                ->setUnitPriceToPayAggregation(200)
                ->setSumPriceToPayAggregation(300),
            (new ItemTransfer())
                ->setRelatedBundleItemIdentifier(ProductBundleBusinessTester::FAKE_BUNDLE_ITEM_IDENTIFIER_1)
                ->setUnitPrice(200)
                ->setSumPrice(250)
                ->setUnitNetPrice(300)
                ->setSumNetPrice(350)
                ->setUnitGrossPrice(200)
                ->setSumGrossPrice(250)
                ->setUnitSubtotalAggregation(200)
                ->setSumSubtotalAggregation(300)
                ->setUnitDiscountAmountFullAggregation(250)
                ->setSumDiscountAmountFullAggregation(350)
                ->setUnitDiscountAmountAggregation(300)
                ->setSumDiscountAmountAggregation(200)
                ->setUnitPriceToPayAggregation(300)
                ->setSumPriceToPayAggregation(400),
            (new ItemTransfer())
                ->setUnitPrice(100)
                ->setSumPrice(150)
                ->setUnitNetPrice(200)
                ->setSumNetPrice(250)
                ->setUnitGrossPrice(100)
                ->setSumGrossPrice(150)
                ->setUnitSubtotalAggregation(100)
                ->setSumSubtotalAggregation(200)
                ->setUnitDiscountAmountFullAggregation(150)
                ->setSumDiscountAmountFullAggregation(250)
                ->setUnitDiscountAmountAggregation(200)
                ->setSumDiscountAmountAggregation(100)
                ->setUnitPriceToPayAggregation(200)
                ->setSumPriceToPayAggregation(300),
        ]);

        // Act
        $calculableObjectTransfer = $this->getProductBundleFacade()->calculateBundlePriceForCalculableObjectTransfer(
            (new CalculableObjectTransfer())
                ->setBundleItems($bundleItemTransfers)
                ->setItems($itemTransfers),
        );

        // Assert
        $this->assertItemPrices($calculableObjectTransfer->getBundleItems()->offsetGet(0), [
            ItemTransfer::UNIT_PRICE => 300,
            ItemTransfer::SUM_PRICE => 400,
            ItemTransfer::UNIT_NET_PRICE => 500,
            ItemTransfer::SUM_NET_PRICE => 600,
            ItemTransfer::UNIT_GROSS_PRICE => 300,
            ItemTransfer::SUM_GROSS_PRICE => 400,
            ItemTransfer::UNIT_SUBTOTAL_AGGREGATION => 300,
            ItemTransfer::SUM_SUBTOTAL_AGGREGATION => 500,
            ItemTransfer::UNIT_DISCOUNT_AMOUNT_FULL_AGGREGATION => 400,
            ItemTransfer::SUM_DISCOUNT_AMOUNT_FULL_AGGREGATION => 600,
            ItemTransfer::UNIT_DISCOUNT_AMOUNT_AGGREGATION => 500,
            ItemTransfer::SUM_DISCOUNT_AMOUNT_AGGREGATION => 300,
            ItemTransfer::UNIT_PRICE_TO_PAY_AGGREGATION => 500,
            ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 700,
        ]);
        $this->assertItemPrices($calculableObjectTransfer->getBundleItems()->offsetGet(1), [
            ItemTransfer::UNIT_PRICE => 0,
            ItemTransfer::SUM_PRICE => 0,
            ItemTransfer::UNIT_NET_PRICE => 0,
            ItemTransfer::SUM_NET_PRICE => 0,
            ItemTransfer::UNIT_GROSS_PRICE => 0,
            ItemTransfer::SUM_GROSS_PRICE => 0,
            ItemTransfer::UNIT_SUBTOTAL_AGGREGATION => 0,
            ItemTransfer::SUM_SUBTOTAL_AGGREGATION => 0,
            ItemTransfer::UNIT_DISCOUNT_AMOUNT_FULL_AGGREGATION => 0,
            ItemTransfer::SUM_DISCOUNT_AMOUNT_FULL_AGGREGATION => 0,
            ItemTransfer::UNIT_DISCOUNT_AMOUNT_AGGREGATION => 0,
            ItemTransfer::SUM_DISCOUNT_AMOUNT_AGGREGATION => 0,
            ItemTransfer::UNIT_PRICE_TO_PAY_AGGREGATION => 0,
            ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 0,
        ]);
    }

    /**
     * @return void
     */
    public function testPreCheckBundledProductPricesShouldBeSuccessfulWhenAllRelatedProductsHavePrices(): void
    {
        // Arrange
        $currencyTransfer = $this->tester->haveCurrencyTransfer([CurrencyTransfer::CODE => ProductBundleBusinessTester::FAKE_CURRENCY_CODE]);

        $bundleProductTransfer = $this->tester->createProductBundle(
            ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_2,
            true,
            false,
            [
                $this->tester->createProduct(
                    ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_1,
                    ProductBundleBusinessTester::SKU_BUNDLED_1,
                    true,
                    true,
                    ProductBundleBusinessTester::DEFAULT_PRODUCT_AVAILABILITY,
                    $currencyTransfer,
                ),
                $this->tester->createProduct(
                    ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_2,
                    ProductBundleBusinessTester::SKU_BUNDLED_2,
                    true,
                    true,
                    ProductBundleBusinessTester::DEFAULT_PRODUCT_AVAILABILITY,
                    $currencyTransfer,
                ),
            ],
        );

        $itemTransfers = new ArrayObject([
            (new ItemTransfer())
                ->setBundleItemIdentifier(ProductBundleBusinessTester::FAKE_BUNDLE_ITEM_IDENTIFIER_1)
                ->setSku($bundleProductTransfer->getSkuOrFail())
                ->setQuantity(1),
            (new ItemTransfer())
                ->setBundleItemIdentifier(ProductBundleBusinessTester::FAKE_BUNDLE_ITEM_IDENTIFIER_2)
                ->setSku($bundleProductTransfer->getSkuOrFail()),
        ]);

        $quoteTransfer = $this->tester->createBaseQuoteTransfer()
            ->setPriceMode(static::GROSS_MODE)
            ->setCurrency($currencyTransfer);

        $cartChangeTransfer = (new CartChangeTransfer())
            ->setItems($itemTransfers)
            ->setQuote($quoteTransfer);

        // Act
        $cartPreCheckResponseTransfer = $this->getProductBundleFacade()->preCheckBundledProductPrices($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testPreCheckBundledProductPricesShouldBeNotSuccessfulWhenAtLeastOneRelatedProductDoesNotHavePrice(): void
    {
        // Arrange
        $currencyTransfer = $this->tester->haveCurrencyTransfer([CurrencyTransfer::CODE => ProductBundleBusinessTester::FAKE_CURRENCY_CODE]);

        $bundleProductTransfer = $this->tester->createProductBundle(
            ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_1,
            true,
            false,
            [
                $this->tester->createProduct(
                    ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_1,
                    ProductBundleBusinessTester::SKU_BUNDLED_1,
                    true,
                    true,
                    ProductBundleBusinessTester::DEFAULT_PRODUCT_AVAILABILITY,
                    $currencyTransfer,
                ),
            ],
        );

        $secondBundleProductTransfer = $this->tester->createProductBundle(
            ProductBundleBusinessTester::BUNDLED_PRODUCT_PRICE_2,
            true,
            false,
            [
                $this->tester->createProduct(
                    0,
                    ProductBundleBusinessTester::SKU_BUNDLED_2,
                    true,
                    true,
                    ProductBundleBusinessTester::DEFAULT_PRODUCT_AVAILABILITY,
                    $currencyTransfer,
                ),
            ],
            ProductBundleBusinessTester::SKU_BUNDLED_4,
        );

        $itemTransfers = new ArrayObject([
            (new ItemTransfer())
                ->setBundleItemIdentifier(ProductBundleBusinessTester::FAKE_BUNDLE_ITEM_IDENTIFIER_1)
                ->setSku($bundleProductTransfer->getSkuOrFail())
                ->setQuantity(1),
            (new ItemTransfer())
                ->setBundleItemIdentifier(ProductBundleBusinessTester::FAKE_BUNDLE_ITEM_IDENTIFIER_2)
                ->setSku($secondBundleProductTransfer->getSkuOrFail())
                ->setQuantity(2),
            (new ItemTransfer())
                ->setBundleItemIdentifier(ProductBundleBusinessTester::FAKE_BUNDLE_ITEM_IDENTIFIER_1)
                ->setSku($bundleProductTransfer->getSkuOrFail()),
        ]);

        $quoteTransfer = $this->tester->createBaseQuoteTransfer()
            ->setPriceMode(static::GROSS_MODE)
            ->setCurrency($currencyTransfer);

        $cartChangeTransfer = (new CartChangeTransfer())
            ->setItems($itemTransfers)
            ->setQuote($quoteTransfer);

        // Act
        $cartPreCheckResponseTransfer = $this->getProductBundleFacade()->preCheckBundledProductPrices($cartChangeTransfer);

        // Assert
        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
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
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array<string, int> $expectedPrices
     *
     * @return void
     */
    protected function assertItemPrices(ItemTransfer $itemTransfer, array $expectedPrices): void
    {
        foreach ($expectedPrices as $priceType => $expectedPrice) {
            $this->assertSame($expectedPrice, $itemTransfer[$priceType]);
        }
    }
}
