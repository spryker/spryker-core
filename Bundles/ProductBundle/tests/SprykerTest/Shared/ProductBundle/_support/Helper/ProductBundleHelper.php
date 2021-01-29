<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ProductBundle\Helper;

use ArrayObject;
use Codeception\Lib\ModuleContainer;
use Codeception\Module;
use Faker\Factory;
use Generated\Shared\DataBuilder\ProductBundleBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductForBundleTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use SprykerTest\Shared\Availability\Helper\AvailabilityDataHelper;
use SprykerTest\Shared\Currency\Helper\CurrencyDataHelper;
use SprykerTest\Shared\PriceProduct\Helper\PriceProductDataHelper;
use SprykerTest\Shared\Product\Helper\ProductDataHelper;
use SprykerTest\Shared\Stock\Helper\StockDataHelper;
use SprykerTest\Shared\Store\Helper\StoreDataHelper;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductBundleHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * @param \Codeception\Lib\ModuleContainer $moduleContainer
     * @param array|null $config
     */
    public function __construct(ModuleContainer $moduleContainer, $config = null)
    {
        parent::__construct($moduleContainer, $config);

        $this->faker = Factory::create();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function haveProductBundle(ProductConcreteTransfer $productConcreteTransfer, array $seed = []): ProductConcreteTransfer
    {
        $productBundleTransfer = (new ProductBundleBuilder($seed))->build();

        if ($productBundleTransfer->getIsNeverOutOfStock() === null) {
            $productBundleTransfer->setIsNeverOutOfStock(true);
        }

        if (!$productBundleTransfer->getBundledProducts()->count()) {
            $productBundleTransfer->setBundledProducts($this->createProductForBundleTransfers());
        }

        $productConcreteTransfer->setProductBundle($productBundleTransfer);

        return $this->getLocator()->productBundle()->facade()->saveBundledProducts($productConcreteTransfer);
    }

    /**
     * @param int $count
     *
     * @return \ArrayObject
     */
    protected function createProductForBundleTransfers(int $count = 3): ArrayObject
    {
        $productForBundleTransfers = new ArrayObject();

        while ($count > 0) {
            $productConcreteTransfer = $this->createProduct();

            $productForBundleTransfer = (new ProductForBundleTransfer())
                ->setIdProductConcrete($productConcreteTransfer->getIdProductConcrete())
                ->setQuantity($this->faker->numberBetween(1, 5));

            $productForBundleTransfers->append($productForBundleTransfer);

            $count--;
        }

        return $productForBundleTransfers;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function createProduct(): ProductConcreteTransfer
    {
        $currencyTransfer = $this->getCurrencyDataHelper()->haveCurrencyTransfer([CurrencyTransfer::CODE => 'EUR']);
        $storeTransfer = $this->getStoreDataHelper()->haveStore([StoreTransfer::NAME => 'DE']);

        $productConcreteTransfer = $this->getProductDataHelper()->haveProduct([
            ProductConcreteTransfer::SKU => $this->faker->slug(),
            ProductConcreteTransfer::IS_ACTIVE => true,
        ]);

        $this->getStockDataHelper()->haveProductInStockForStore($storeTransfer, [
            StockProductTransfer::SKU => $productConcreteTransfer->getSku(),
            StockProductTransfer::QUANTITY => 10,
            StockProductTransfer::IS_NEVER_OUT_OF_STOCK => true,
        ]);

        $this->getAvailabilityDataHelper()->haveAvailabilityConcrete($productConcreteTransfer->getSku(), $storeTransfer, 10);

        $this->getPriceProductDataHelper()->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => $this->faker->numberBetween(1000, 5000),
                MoneyValueTransfer::GROSS_AMOUNT => $this->faker->numberBetween(1000, 5000),
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);

        return $productConcreteTransfer;
    }

    /**
     * @return \SprykerTest\Shared\Currency\Helper\CurrencyDataHelper
     */
    protected function getCurrencyDataHelper(): CurrencyDataHelper
    {
        /** @var \SprykerTest\Shared\Currency\Helper\CurrencyDataHelper $helper */
        $helper = $this->getModule('\\' . CurrencyDataHelper::class);

        return $helper;
    }

    /**
     * @return \SprykerTest\Shared\Store\Helper\StoreDataHelper
     */
    protected function getStoreDataHelper(): StoreDataHelper
    {
        /** @var \SprykerTest\Shared\Store\Helper\StoreDataHelper $helper */
        $helper = $this->getModule('\\' . StoreDataHelper::class);

        return $helper;
    }

    /**
     * @return \SprykerTest\Shared\Product\Helper\ProductDataHelper
     */
    protected function getProductDataHelper(): ProductDataHelper
    {
        /** @var \SprykerTest\Shared\Product\Helper\ProductDataHelper $helper */
        $helper = $this->getModule('\\' . ProductDataHelper::class);

        return $helper;
    }

    /**
     * @return \SprykerTest\Shared\Stock\Helper\StockDataHelper
     */
    protected function getStockDataHelper(): StockDataHelper
    {
        /** @var \SprykerTest\Shared\Stock\Helper\StockDataHelper $helper */
        $helper = $this->getModule('\\' . StockDataHelper::class);

        return $helper;
    }

    /**
     * @return \SprykerTest\Shared\Availability\Helper\AvailabilityDataHelper
     */
    protected function getAvailabilityDataHelper(): AvailabilityDataHelper
    {
        /** @var \SprykerTest\Shared\Availability\Helper\AvailabilityDataHelper $helper */
        $helper = $this->getModule('\\' . AvailabilityDataHelper::class);

        return $helper;
    }

    /**
     * @return \SprykerTest\Shared\PriceProduct\Helper\PriceProductDataHelper
     */
    protected function getPriceProductDataHelper(): PriceProductDataHelper
    {
        /** @var \SprykerTest\Shared\PriceProduct\Helper\PriceProductDataHelper $helper */
        $helper = $this->getModule('\\' . PriceProductDataHelper::class);

        return $helper;
    }
}
