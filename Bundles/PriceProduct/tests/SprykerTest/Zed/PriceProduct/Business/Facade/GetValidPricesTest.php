<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use ReflectionClass;
use Spryker\Zed\PriceProduct\Business\Model\Reader;
use SprykerTest\Zed\PriceProduct\PriceProductBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProduct
 * @group Business
 * @group Facade
 * @group GetValidPricesTest
 * Add your own group annotations below this line
 */
class GetValidPricesTest extends Unit
{
    /**
     * @var string
     */
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @var int
     */
    protected const COUNT_PRODUCT_WITH_PRICES = 5;

    /**
     * @var string
     */
    protected const DEFAULT_STORE = 'DE';

    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected PriceProductBusinessTester $tester;

    /**
     * @return void
     */
    public function testGetValidPricesReturnsCollectionOfValidTransfers(): void
    {
        // Arrange
        $priceProductTransfers = [];
        for ($i = 0; $i < static::COUNT_PRODUCT_WITH_PRICES; $i++) {
            $grossPrice = rand(10, 100);
            $netPrice = $grossPrice - rand(1, 9);
            $priceProductTransfers[] = $this->tester->createProductWithAmount(
                $grossPrice,
                $netPrice,
                '',
                '',
                PriceProductBusinessTester::EUR_ISO_CODE,
            );
        }
        $priceProductFilterTransfers = [];
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DEFAULT_STORE]);
        foreach ($priceProductTransfers as $priceProductTransfer) {
            $priceProductFilterTransfer = (new PriceProductFilterTransfer())
                ->setCurrencyIsoCode(PriceProductBusinessTester::EUR_ISO_CODE)
                ->setSku($priceProductTransfer->getSkuProduct())
                ->setPriceMode(static::PRICE_MODE_GROSS);
            if ($this->tester->isDynamicStoreEnabled()) {
                $priceProductFilterTransfer->setStoreName($storeTransfer->getName());
            }
            $priceProductFilterTransfers[] = $priceProductFilterTransfer;
        }

        // Act
        $resultPriceProductPrices = $this->tester->getFacade()->getValidPrices($priceProductFilterTransfers);

        // Assert
        $this->assertCount(count($priceProductTransfers), $resultPriceProductPrices);
    }

    /**
     * @return void
     */
    public function testGetValidPricesReturnsCollectionOfValidTransfersWithNumericSKUs(): void
    {
        // Arrange
        $priceProductTransfers = [];
        for ($i = 1; $i <= static::COUNT_PRODUCT_WITH_PRICES; $i++) {
            $grossPrice = rand(10, 100);
            $netPrice = $grossPrice - rand(1, 9);
            $skuAbstract = $i . '9000';
            $productConcreteTransfer = $this->tester->haveProduct([], ['sku' => $skuAbstract]);
            $priceProductTransfers[] = $this->tester->createProductWithAmount(
                $grossPrice,
                $netPrice,
                $productConcreteTransfer->getAbstractSku(),
                '',
                PriceProductBusinessTester::EUR_ISO_CODE,
            );
        }
        $priceProductFilterTransfers = [];
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DEFAULT_STORE]);
        foreach ($priceProductTransfers as $priceProductTransfer) {
            $priceProductFilterTransfer = (new PriceProductFilterTransfer())
                ->setCurrencyIsoCode(PriceProductBusinessTester::EUR_ISO_CODE)
                ->setSku($priceProductTransfer->getSkuProduct())
                ->setPriceMode(static::PRICE_MODE_GROSS);

            if ($this->tester->isDynamicStoreEnabled()) {
                $priceProductFilterTransfer->setStoreName($storeTransfer->getName());
            }

            $priceProductFilterTransfers[] = $priceProductFilterTransfer;
        }

        // Act
        $resultPriceProductPrices = $this->tester->getFacade()->getValidPrices($priceProductFilterTransfers);

        // Assert
        $this->assertCount(count($priceProductTransfers), $resultPriceProductPrices);
    }

    /**
     * @return void
     */
    public function testGetValidPricesReturnsProductPricesUsingAbstractProduct(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();

        $priceProductTransfer = $this->tester->createPriceProductForAbstractProduct(
            $productConcreteTransfer->getAbstractSku(),
            $productConcreteTransfer->getFkProductAbstract(),
        );

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DEFAULT_STORE]);
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setCurrencyIsoCode(PriceProductBusinessTester::EUR_ISO_CODE)
            ->setSku($productConcreteTransfer->getSku())
            ->setPriceMode(static::PRICE_MODE_GROSS);
        if ($this->tester->isDynamicStoreEnabled()) {
            $priceProductFilterTransfer->setStoreName($storeTransfer->getName());
        }

        // Act
        $resultPriceProductTransfers = $this->tester->getFacade()->getValidPrices([$priceProductFilterTransfer]);

        // Assert
        $this->assertCount(1, $resultPriceProductTransfers);
        /** @var \Generated\Shared\Transfer\PriceProductTransfer $resultPriceProductTransfer */
        $resultPriceProductTransfer = $resultPriceProductTransfers[0];

        $this->assertSame($priceProductTransfer->getIdProductAbstract(), $resultPriceProductTransfer->getIdProductAbstract());
        $this->assertSame(
            $priceProductTransfer->getMoneyValue()->getNetAmount(),
            $resultPriceProductTransfer->getMoneyValue()->getNetAmount(),
        );
        $this->assertSame(
            $priceProductTransfer->getMoneyValue()->getGrossAmount(),
            $resultPriceProductTransfer->getMoneyValue()->getGrossAmount(),
        );
    }

    /**
     * @return void
     */
    public function testGetValidPricesReturnsProductPricesUsingConcreteProduct(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();

        $priceProductTransfer = $this->tester->createPriceProductForConcreteProduct(
            $productConcreteTransfer->getAbstractSku(),
            $productConcreteTransfer->getIdProductConcrete(),
        );

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DEFAULT_STORE]);
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setCurrencyIsoCode(PriceProductBusinessTester::EUR_ISO_CODE)
            ->setSku($productConcreteTransfer->getSku())
            ->setPriceMode(static::PRICE_MODE_GROSS);
        if ($this->tester->isDynamicStoreEnabled()) {
            $priceProductFilterTransfer->setStoreName($storeTransfer->getName());
        }

        // Act
        $resultPriceProductTransfers = $this->tester->getFacade()->getValidPrices([$priceProductFilterTransfer]);

        // Assert
        $this->assertCount(1, $resultPriceProductTransfers);
        /** @var \Generated\Shared\Transfer\PriceProductTransfer $resultPriceProductTransfer */
        $resultPriceProductTransfer = $resultPriceProductTransfers[0];

        $this->assertSame($priceProductTransfer->getIdProduct(), $resultPriceProductTransfer->getIdProduct());
        $this->assertSame(
            $priceProductTransfer->getMoneyValue()->getNetAmount(),
            $resultPriceProductTransfer->getMoneyValue()->getNetAmount(),
        );
        $this->assertSame(
            $priceProductTransfer->getMoneyValue()->getGrossAmount(),
            $resultPriceProductTransfer->getMoneyValue()->getGrossAmount(),
        );
    }

    /**
     * @return void
     */
    public function testGetValidPricesReturnsProductPricesMergingConcreteWithAbstract(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();

        $this->tester->createPriceProductForAbstractProduct(
            $productConcreteTransfer->getAbstractSku(),
            $productConcreteTransfer->getFkProductAbstract(),
        );

        $priceProductTransfer = $this->tester->createPriceProductForConcreteProduct(
            $productConcreteTransfer->getAbstractSku(),
            $productConcreteTransfer->getIdProductConcrete(),
            $productConcreteTransfer->getSku(),
        );

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DEFAULT_STORE]);
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setCurrencyIsoCode(PriceProductBusinessTester::EUR_ISO_CODE)
            ->setSku($productConcreteTransfer->getSku())
            ->setPriceMode(static::PRICE_MODE_GROSS);
        if ($this->tester->isDynamicStoreEnabled()) {
            $priceProductFilterTransfer->setStoreName($storeTransfer->getName());
        }

        // Act
        $resultPriceProductTransfers = $this->tester->getFacade()->getValidPrices([$priceProductFilterTransfer]);

        // Assert
        $this->assertCount(1, $resultPriceProductTransfers);
        /** @var \Generated\Shared\Transfer\PriceProductTransfer $resultPriceProductTransfer */
        $resultPriceProductTransfer = $resultPriceProductTransfers[0];

        $this->assertSame(
            $priceProductTransfer->getMoneyValue()->getNetAmount(),
            $resultPriceProductTransfer->getMoneyValue()->getNetAmount(),
        );
        $this->assertSame(
            $priceProductTransfer->getMoneyValue()->getGrossAmount(),
            $resultPriceProductTransfer->getMoneyValue()->getGrossAmount(),
        );
    }

    /**
     * @return void
     */
    public function testGetValidPricesReturnsProductPricesMergingConcreteWithAbstractWithDifferentCurrencies(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();

        $priceProductTransfer = $this->tester->createPriceProductForAbstractProduct(
            $productConcreteTransfer->getAbstractSku(),
            $productConcreteTransfer->getFkProductAbstract(),
            PriceProductBusinessTester::EUR_ISO_CODE,
        );

        $this->tester->createPriceProductForConcreteProduct(
            $productConcreteTransfer->getAbstractSku(),
            $productConcreteTransfer->getIdProductConcrete(),
            $productConcreteTransfer->getSku(),
            PriceProductBusinessTester::USD_ISO_CODE,
        );

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DEFAULT_STORE]);
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setCurrencyIsoCode(PriceProductBusinessTester::EUR_ISO_CODE)
            ->setSku($productConcreteTransfer->getSku())
            ->setPriceMode(static::PRICE_MODE_GROSS);
        if ($this->tester->isDynamicStoreEnabled()) {
            $priceProductFilterTransfer->setStoreName($storeTransfer->getName());
        }

        // Act
        $resultPriceProductTransfers = $this->tester->getFacade()->getValidPrices([$priceProductFilterTransfer]);

        // Assert
        $this->assertCount(1, $resultPriceProductTransfers);
        /** @var \Generated\Shared\Transfer\PriceProductTransfer $resultPriceProductTransfer */
        $resultPriceProductTransfer = $resultPriceProductTransfers[0];

        $this->assertSame(
            $priceProductTransfer->getMoneyValue()->getFkCurrency(),
            $resultPriceProductTransfer->getMoneyValue()->getFkCurrency(),
        );
        $this->assertSame(
            $priceProductTransfer->getMoneyValue()->getNetAmount(),
            $resultPriceProductTransfer->getMoneyValue()->getNetAmount(),
        );
        $this->assertSame(
            $priceProductTransfer->getMoneyValue()->getGrossAmount(),
            $resultPriceProductTransfer->getMoneyValue()->getGrossAmount(),
        );
    }

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->clearProductPriceTransferCache();
    }

    /**
     * @return void
     */
    protected function clearProductPriceTransferCache(): void
    {
        $reflectionClass = new ReflectionClass(Reader::class);
        $reflectionProperty = $reflectionClass->getProperty('resolvedPriceProductTransferCollection');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue([]);
    }
}
