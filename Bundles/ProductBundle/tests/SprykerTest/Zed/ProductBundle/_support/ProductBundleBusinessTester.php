<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle;

use Codeception\Actor;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductBundleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductForBundleTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 *
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface getFacade()
 */
class ProductBundleBusinessTester extends Actor
{
    use _generated\ProductBundleBusinessTesterActions;

    public const SKU_BUNDLED_1 = 'sku-1-test-tester';
    public const SKU_BUNDLED_2 = 'sku-2-test-tester';
    public const BUNDLE_SKU_3 = 'sku-3-test-tester';
    public const BUNDLED_PRODUCT_PRICE_1 = 50;
    public const BUNDLED_PRODUCT_PRICE_2 = 100;

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
}
