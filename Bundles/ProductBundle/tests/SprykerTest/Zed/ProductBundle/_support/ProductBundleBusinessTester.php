<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\OrderBuilder;
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
use Spryker\Zed\ProductBundle\Business\ProductBundle\Cache\ProductBundleCache;
use Spryker\Zed\ProductBundle\Communication\Plugin\Checkout\ProductBundleOrderSaverPlugin;
use SprykerTest\Zed\Sales\Helper\BusinessHelper;

/**
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
 * @SuppressWarnings(\SprykerTest\Zed\ProductBundle\PHPMD)
 */
class ProductBundleBusinessTester extends Actor
{
    use _generated\ProductBundleBusinessTesterActions;

    /**
     * @var string
     */
    public const SKU_BUNDLED_1 = 'sku-1-test-tester';

    /**
     * @var string
     */
    public const SKU_BUNDLED_2 = 'sku-2-test-tester';

    /**
     * @var string
     */
    public const BUNDLE_SKU_3 = 'sku-3-test-tester';

    /**
     * @var string
     */
    public const SKU_BUNDLED_4 = 'sku-4-test-tester';

    /**
     * @var int
     */
    public const BUNDLED_PRODUCT_PRICE_1 = 50;

    /**
     * @var int
     */
    public const BUNDLED_PRODUCT_PRICE_2 = 100;

    /**
     * @var string
     */
    public const FAKE_BUNDLE_ITEM_IDENTIFIER_1 = 'FAKE_BUNDLE_ITEM_IDENTIFIER_1';

    /**
     * @var string
     */
    public const FAKE_BUNDLE_ITEM_IDENTIFIER_2 = 'FAKE_BUNDLE_ITEM_IDENTIFIER_2';

    /**
     * @var string
     */
    public const FAKE_PRODUCT_OPTION_SKU_1 = 'FAKE_PRODUCT_OPTION_SKU_1';

    /**
     * @var string
     */
    public const FAKE_PRODUCT_OPTION_SKU_2 = 'FAKE_PRODUCT_OPTION_SKU_2';

    /**
     * @var string
     */
    public const FAKE_CURRENCY_CODE = 'FAKE';

    /**
     * @var int
     */
    public const DEFAULT_PRODUCT_AVAILABILITY = 10;

    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @param int $bundlePrice
     * @param bool $isAlwaysAvailable
     * @param bool $isNeverOutOfStock
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productsToAssign
     * @param string|null $bundleProductSku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function createProductBundle(
        int $bundlePrice,
        bool $isAlwaysAvailable = false,
        bool $isNeverOutOfStock = false,
        array $productsToAssign = [],
        ?string $bundleProductSku = null
    ): ProductConcreteTransfer {
        if ($productsToAssign === []) {
            $productsToAssign = [
                $this->createProduct(
                    static::BUNDLED_PRODUCT_PRICE_1,
                    static::SKU_BUNDLED_1,
                    $isAlwaysAvailable,
                    $isNeverOutOfStock,
                ),
                $this->createProduct(
                    static::BUNDLED_PRODUCT_PRICE_2,
                    static::SKU_BUNDLED_2,
                    $isAlwaysAvailable,
                    $isNeverOutOfStock,
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
            $bundledProductTransfer->setSku($productConcreteTransferToAssign->getSku());
            $bundledProductTransfer->setIdProductConcrete($productConcreteTransferToAssign->getIdProductConcrete());
            $productBundleTransfer->addBundledProduct($bundledProductTransfer);
        }

        $productConcreteTransfer = $this->createProduct($bundlePrice, $bundleProductSku ?? static::BUNDLE_SKU_3, $isAlwaysAvailable);
        $productConcreteTransfer->setProductBundle($productBundleTransfer);

        $this->getFacade()->saveBundledProducts($productConcreteTransfer);

        return $productConcreteTransfer;
    }

    /**
     * @param int $price
     * @param string $sku
     * @param bool $isActive
     * @param bool $isNeverOutOfStock
     * @param int $quantity
     * @param \Generated\Shared\Transfer\CurrencyTransfer|null $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function createProduct(
        int $price,
        string $sku,
        bool $isActive = false,
        bool $isNeverOutOfStock = false,
        int $quantity = self::DEFAULT_PRODUCT_AVAILABILITY,
        ?CurrencyTransfer $currencyTransfer = null
    ): ProductConcreteTransfer {
        $productConcreteTransfer = $this->haveProduct([
            ProductConcreteTransfer::SKU => $sku,
            ProductConcreteTransfer::IS_ACTIVE => $isActive,
        ]);

        $storeTransfer = $this->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->haveProductInStockForStore($storeTransfer, [
            StockProductTransfer::SKU => $productConcreteTransfer->getSku(),
            StockProductTransfer::QUANTITY => $quantity,
            StockProductTransfer::IS_NEVER_OUT_OF_STOCK => $isNeverOutOfStock,
        ]);
        $this->haveAvailabilityConcrete($productConcreteTransfer->getSku(), $storeTransfer, $quantity);

        if ($currencyTransfer === null) {
            $currencyTransfer = $this->haveCurrencyTransfer([CurrencyTransfer::CODE => static::FAKE_CURRENCY_CODE]);
        }
        $priceProductTransfer = $this->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => $price,
                MoneyValueTransfer::GROSS_AMOUNT => $price,
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);
        $productConcreteTransfer->addProductAbstractPrice($priceProductTransfer);

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
            [new ProductBundleOrderSaverPlugin()],
        );

        return (new OrderTransfer())
            ->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder())
            ->setOrderReference($saveOrderTransfer->getOrderReference())
            ->setCustomer($quoteTransfer->getCustomer())
            ->setItems($saveOrderTransfer->getOrderItems());
    }

    /**
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
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

    /**
     * @param array $bundleItemSeeds
     * @param array $orderSeed
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function buildOrderTransferWithBundleItems(array $bundleItemSeeds, array $orderSeed = []): OrderTransfer
    {
        $orderBuilder = new OrderBuilder($orderSeed);

        foreach ($bundleItemSeeds as $bundleItemSeed) {
            $orderBuilder->withAnotherBundleItem(new ItemBuilder($bundleItemSeed));
        }

        return $orderBuilder->build();
    }

    /**
     * @return void
     */
    public function cleanProductBundleCache(): void
    {
        (new ProductBundleCache())->cleanCache();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createBaseQuoteTransfer(): QuoteTransfer
    {
        return (new QuoteTransfer())
            ->setStore((new StoreTransfer())->setName(static::STORE_NAME_DE));
    }
}
