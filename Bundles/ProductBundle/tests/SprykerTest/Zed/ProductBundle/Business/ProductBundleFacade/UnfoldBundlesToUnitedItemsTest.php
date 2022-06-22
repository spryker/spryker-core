<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\Cart;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductBundleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductForBundleTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Business
 * @group Cart
 * @group UnfoldBundlesToUnitedItemsTest
 * Add your own group annotations below this line
 */
class UnfoldBundlesToUnitedItemsTest extends Unit
{
    /**
     * @var string
     */
    protected const PRODUCT_CONCRETE_SKU_1 = 'PRODUCT_CONCRETE_SKU_1';

    /**
     * @var string
     */
    protected const PRODUCT_CONCRETE_SKU_2 = 'PRODUCT_CONCRETE_SKU_2';

    /**
     * @var string
     */
    protected const BUNDLE_SKU = 'BUNDLE_001';

    /**
     * @var int
     */
    protected const BUNDLED_PRODUCT_PRICE_1 = 50;

    /**
     * @var int
     */
    protected const BUNDLED_PRODUCT_PRICE_2 = 100;

    /**
     * @var int
     */
    protected const QUANTITY_OF_PRODUCTS_PER_BUNDLE = 2;

    /**
     * @var int
     */
    protected const BUNDLE_PRICE = 90;

    /**
     * @var int
     */
    protected const INITIAL_QUANTITY_IN_CART_CHANGE_ITEM = 3;

    /**
     * @var int
     */
    protected const EXPECTED_NEW_BUNDLED_PRODUCT_PRICE_1 = 15;

    /**
     * @var int
     */
    protected const EXPECTED_NEW_BUNDLED_PRODUCT_PRICE_2 = 30;

    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_GROSS
     *
     * @var string
     */
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @var string
     */
    protected const CURRENCY_CODE_TEST_CODE = 'TEST';

    /**
     * @var \SprykerTest\Zed\ProductBundle\ProductBundleBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testBundleUnfoldedSuccessfullyWhenPresentInCartChangeItems(): void
    {
        // Arrange
        $cartChangeTransfer = $this->setupCartChangeTransferWithBundle();

        // Act
        $cartChangeTransfer = $this->tester->getFacade()->unfoldBundlesToUnitedItems($cartChangeTransfer);

        // Assert
        $this->assertCount(
            1,
            $cartChangeTransfer->getQuote()->getBundleItems(),
            'Quote.bundleItems should contain one element',
        );

        $this->assertCount(
            static::QUANTITY_OF_PRODUCTS_PER_BUNDLE * 2,
            $cartChangeTransfer->getItems(),
            'Number of cart change transfer items should be equal to total number of products in one bundle',
        );

        $expectedBundledItemPrice = [
            static::PRODUCT_CONCRETE_SKU_1 => static::EXPECTED_NEW_BUNDLED_PRODUCT_PRICE_1,
            static::PRODUCT_CONCRETE_SKU_2 => static::EXPECTED_NEW_BUNDLED_PRODUCT_PRICE_2,
        ];

        /** @var \Generated\Shared\Transfer\ItemTransfer $bundleItemTransfer */
        $bundleItemTransfer = $cartChangeTransfer->getQuote()->getBundleItems()->offsetGet(0);
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $this->assertTrue(
                isset($expectedBundledItemPrice[$itemTransfer->getSku()]),
                'A bundle in CartChangeTransfer.items should be replaced with bundled products',
            );

            $this->assertEquals(
                $expectedBundledItemPrice[$itemTransfer->getSku()],
                $itemTransfer->getUnitGrossPrice(),
                'Bundle price should be distributed proportionally between all bundled products',
            );

            $this->assertEquals(
                static::INITIAL_QUANTITY_IN_CART_CHANGE_ITEM,
                $itemTransfer->getQuantity(),
                'Bundled item quantity should be equal to initial bundle quantity',
            );

            $this->assertEquals(
                $bundleItemTransfer->getBundleItemIdentifier(),
                $itemTransfer->getRelatedBundleItemIdentifier(),
                'Related bundle item identifier should be set for a bundled item',
            );
        }
    }

    /**
     * @return void
     */
    public function testCartChangeTransferNotChangedWhenNoBundlePresent(): void
    {
        // Arrange
        $cartChangeTransfer = $this->setupCartChangeTransferWithoutBundle();
        $initialCartChangeTransfer = (new CartChangeTransfer())->fromArray($cartChangeTransfer->toArray());

        // Act
        $cartChangeTransfer = $this->tester->getFacade()->unfoldBundlesToUnitedItems($cartChangeTransfer);

        // Assert
        $this->assertEquals(
            $initialCartChangeTransfer->toArray(),
            $cartChangeTransfer->toArray(),
            'Cart change transfer should not be changed when no bundles to unfold',
        );
    }

    /**
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function setupCartChangeTransferWithBundle(): CartChangeTransfer
    {
        $currencyTransfer = $this->tester->haveCurrencyTransfer([
            CurrencyTransfer::CODE => static::CURRENCY_CODE_TEST_CODE,
        ]);
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => static::STORE_NAME_DE,
        ]);

        $productConcreteTransfer1 = $this->createProduct(
            $storeTransfer,
            $currencyTransfer,
            static::BUNDLED_PRODUCT_PRICE_1,
            static::PRODUCT_CONCRETE_SKU_1,
        );

        $productConcreteTransfer2 = $this->createProduct(
            $storeTransfer,
            $currencyTransfer,
            static::BUNDLED_PRODUCT_PRICE_2,
            static::PRODUCT_CONCRETE_SKU_2,
        );

        $bundleTransfer = $this->createProductBundle(
            $storeTransfer,
            $currencyTransfer,
            [$productConcreteTransfer1, $productConcreteTransfer2],
            static::BUNDLE_PRICE,
        );

        $quoteTransfer = $this->createQuote($storeTransfer, $currencyTransfer);

        $cartChangeSeed = [
            ItemTransfer::ID => $bundleTransfer->getIdProductConcrete(),
            ItemTransfer::SKU => $bundleTransfer->getSku(),
            ItemTransfer::GROUP_KEY => $bundleTransfer->getSku(),
            ItemTransfer::QUANTITY => static::INITIAL_QUANTITY_IN_CART_CHANGE_ITEM,
            ItemTransfer::UNIT_GROSS_PRICE => static::BUNDLE_PRICE,
        ];
        $itemTransfer = (new ItemBuilder($cartChangeSeed))
            ->build();

        return (new CartChangeTransfer())
            ->setQuote($quoteTransfer)
            ->setItems(new ArrayObject([$itemTransfer]));
    }

    /**
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function setupCartChangeTransferWithoutBundle(): CartChangeTransfer
    {
        $currencyTransfer = $this->tester->haveCurrencyTransfer([
            CurrencyTransfer::CODE => static::CURRENCY_CODE_TEST_CODE,
        ]);
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => static::STORE_NAME_DE,
        ]);

        $productConcreteTransfer1 = $this->createProduct(
            $storeTransfer,
            $currencyTransfer,
            static::BUNDLED_PRODUCT_PRICE_1,
            static::PRODUCT_CONCRETE_SKU_1,
        );

        $quoteTransfer = $this->createQuote($storeTransfer, $currencyTransfer);

        $cartChangeSeed = [
            ItemTransfer::ID => $productConcreteTransfer1->getIdProductConcrete(),
            ItemTransfer::SKU => $productConcreteTransfer1->getSku(),
            ItemTransfer::GROUP_KEY => $productConcreteTransfer1->getSku(),
            ItemTransfer::QUANTITY => static::INITIAL_QUANTITY_IN_CART_CHANGE_ITEM,
            ItemTransfer::UNIT_GROSS_PRICE => static::BUNDLED_PRODUCT_PRICE_1,
        ];
        $itemTransfer = (new ItemBuilder($cartChangeSeed))
            ->build();

        return (new CartChangeTransfer())
            ->setQuote($quoteTransfer)
            ->setItems(new ArrayObject([$itemTransfer]));
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuote(StoreTransfer $storeTransfer, CurrencyTransfer $currencyTransfer): QuoteTransfer
    {
        return (new QuoteTransfer())
            ->setPriceMode(static::PRICE_MODE_GROSS)
            ->setStore($storeTransfer)
            ->setCurrency($currencyTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int $price
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function createProduct(
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        int $price,
        string $sku
    ): ProductConcreteTransfer {
        $productConcreteTransfer = $this->tester->haveProduct([
            ProductConcreteTransfer::SKU => $sku,
            ProductConcreteTransfer::IS_ACTIVE => true,
        ]);

        $this->tester->haveProductInStockForStore($storeTransfer, [
            StockProductTransfer::SKU => $productConcreteTransfer->getSku(),
            StockProductTransfer::QUANTITY => 10,
            StockProductTransfer::IS_NEVER_OUT_OF_STOCK => true,
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
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productsToAssign
     * @param int $bundlePrice
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function createProductBundle(
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        array $productsToAssign,
        int $bundlePrice
    ): ProductConcreteTransfer {
        $productBundleTransfer = new ProductBundleTransfer();
        $productBundleTransfer->setIsNeverOutOfStock(true);

        foreach ($productsToAssign as $productConcreteTransferToAssign) {
            $bundledProductTransfer = new ProductForBundleTransfer();
            $bundledProductTransfer->setQuantity(static::QUANTITY_OF_PRODUCTS_PER_BUNDLE);
            $bundledProductTransfer->setIdProductConcrete($productConcreteTransferToAssign->getIdProductConcrete());
            $productBundleTransfer->addBundledProduct($bundledProductTransfer);
        }

        $productConcreteTransfer = $this->createProduct($storeTransfer, $currencyTransfer, $bundlePrice, static::BUNDLE_SKU);
        $productConcreteTransfer->setProductBundle($productBundleTransfer);

        $this->tester->getFacade()->saveBundledProducts($productConcreteTransfer);

        return $productConcreteTransfer;
    }
}
