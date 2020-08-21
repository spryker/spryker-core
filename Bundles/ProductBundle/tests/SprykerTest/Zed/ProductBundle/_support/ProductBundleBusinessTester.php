<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductBundleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductForBundleTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductBundle\Communication\Plugin\Checkout\ProductBundleOrderSaverPlugin;
use SprykerTest\Zed\Sales\Helper\BusinessHelper;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductBundleBusinessTester extends Actor
{
    use _generated\ProductBundleBusinessTesterActions;

    public const SKU_BUNDLED_1 = 'sku-1-test-tester';
    public const SKU_BUNDLED_2 = 'sku-2-test-tester';
    public const BUNDLE_SKU_3 = 'sku-3-test-tester';
    public const BUNDLED_PRODUCT_PRICE_1 = 50;
    public const BUNDLED_PRODUCT_PRICE_2 = 100;

    public const FAKE_BUNDLE_ITEM_IDENTIFIER_1 = 'FAKE_BUNDLE_ITEM_IDENTIFIER_1';
    public const FAKE_BUNDLE_ITEM_IDENTIFIER_2 = 'FAKE_BUNDLE_ITEM_IDENTIFIER_2';

    public const FAKE_PRODUCT_OPTION_SKU_1 = 'FAKE_PRODUCT_OPTION_SKU_1';
    public const FAKE_PRODUCT_OPTION_SKU_2 = 'FAKE_PRODUCT_OPTION_SKU_2';

    protected const STORE_NAME_DE = 'DE';

    /**
     * @param int $bundlePrice
     * @param bool $isAlwaysAvailable
     * @param bool $isNeverOutOfStock
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productsToAssign
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function createProductBundle(
        $bundlePrice,
        $isAlwaysAvailable = false,
        $isNeverOutOfStock = false,
        array $productsToAssign = []
    ): ProductConcreteTransfer {
        if ($productsToAssign === []) {
            $productsToAssign = [
                $this->createProduct(
                    static::BUNDLED_PRODUCT_PRICE_1,
                    static::SKU_BUNDLED_1,
                    $isAlwaysAvailable,
                    $isNeverOutOfStock
                ),
                $this->createProduct(
                    static::BUNDLED_PRODUCT_PRICE_2,
                    static::SKU_BUNDLED_2,
                    $isAlwaysAvailable,
                    $isNeverOutOfStock
                ),
            ];
        }

        $productBundleTransfer = new ProductBundleTransfer();
        if ($isNeverOutOfStock) {
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

        $this->getFacade()->saveBundledProducts($productConcreteTransfer);

        return $productConcreteTransfer;
    }

    /**
     * @param int $price
     * @param string $sku
     * @param bool $isActive
     * @param bool $isNeverOutOfStock
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function createProduct(
        int $price,
        string $sku,
        bool $isActive = false,
        $isNeverOutOfStock = false
    ): ProductConcreteTransfer {
        $currencyTransfer = $this->haveCurrencyTransfer([CurrencyTransfer::CODE => 'EUR']);
        $productConcreteTransfer = $this->haveProduct([
            ProductConcreteTransfer::SKU => $sku,
            ProductConcreteTransfer::IS_ACTIVE => $isActive,
        ]);

        $storeTransfer = $this->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->haveProductInStockForStore($storeTransfer, [
            StockProductTransfer::SKU => $productConcreteTransfer->getSku(),
            StockProductTransfer::QUANTITY => 10,
            StockProductTransfer::IS_NEVER_OUT_OF_STOCK => $isNeverOutOfStock,
        ]);
        $this->haveAvailabilityConcrete($productConcreteTransfer->getSku(), $storeTransfer, 10);

        $this->havePriceProduct([
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
     * @param array $quoteSeed
     * @param array $itemSeed
     * @param array $bundleItemSeed
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function buildQuote(array $quoteSeed, array $itemSeed, array $bundleItemSeed = []): QuoteTransfer
    {
        $quoteBuilder = (new QuoteBuilder($quoteSeed))
            ->withItem($itemSeed)
            ->withTotals()
            ->withStore()
            ->withCurrency()
            ->withShippingAddress()
            ->withBillingAddress();

        if ($bundleItemSeed) {
            $quoteBuilder->withBundleItem($bundleItemSeed);
        }

        return $quoteBuilder->build();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createOrderFromQuote(QuoteTransfer $quoteTransfer): OrderTransfer
    {
        $saveOrderTransfer = $this->haveOrderFromQuote(
            $quoteTransfer,
            BusinessHelper::DEFAULT_OMS_PROCESS_NAME,
            [new ProductBundleOrderSaverPlugin()]
        );

        return (new OrderTransfer())
            ->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder())
            ->setOrderReference($saveOrderTransfer->getOrderReference())
            ->setCustomer($quoteTransfer->getCustomer())
            ->setItems($saveOrderTransfer->getOrderItems());
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function createBundleItemsWithOptions(): array
    {
        $bundleItem1 = (new ItemTransfer())
            ->setBundleItemIdentifier(static::FAKE_BUNDLE_ITEM_IDENTIFIER_1);
        $bundleItem2 = (new ItemTransfer())
            ->setBundleItemIdentifier(static::FAKE_BUNDLE_ITEM_IDENTIFIER_2);

        return [
            (new ItemTransfer())
                ->setRelatedBundleItemIdentifier(static::FAKE_BUNDLE_ITEM_IDENTIFIER_1)
                ->setProductOptions(new ArrayObject([
                    (new ProductOptionTransfer())->setSku(static::FAKE_PRODUCT_OPTION_SKU_1),
                    (new ProductOptionTransfer())->setSku(static::FAKE_PRODUCT_OPTION_SKU_2),
                ]))
                ->setProductBundle($bundleItem1),
            (new ItemTransfer())
                ->setRelatedBundleItemIdentifier(static::FAKE_BUNDLE_ITEM_IDENTIFIER_1)
                ->setProductBundle($bundleItem1),
            (new ItemTransfer())
                ->setRelatedBundleItemIdentifier(static::FAKE_BUNDLE_ITEM_IDENTIFIER_2)
                ->setProductOptions(new ArrayObject([
                    (new ProductOptionTransfer())->setSku(static::FAKE_PRODUCT_OPTION_SKU_2),
                ]))
                ->setProductBundle($bundleItem2),
            (new ItemTransfer())
                ->setRelatedBundleItemIdentifier(static::FAKE_BUNDLE_ITEM_IDENTIFIER_2)
                ->setProductOptions(new ArrayObject([
                    (new ProductOptionTransfer())->setSku(static::FAKE_PRODUCT_OPTION_SKU_1),
                ]))
                ->setProductBundle($bundleItem2),
        ];
    }
}
