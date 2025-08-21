<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct;

use Codeception\Actor;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\PriceProduct\PriceProductConfig;
use Spryker\Zed\Currency\Business\CurrencyFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

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
 * @method \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface getFacade(?string $moduleName = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class PriceProductBusinessTester extends Actor
{
    use _generated\PriceProductBusinessTesterActions;

    /**
     * @var string
     */
    public const EUR_ISO_CODE = 'EUR';

    /**
     * @var string
     */
    public const USD_ISO_CODE = 'USD';

    /**
     * @var string
     */
    protected const STORE_NAME = 'DE';

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_NET
     *
     * @var string
     */
    protected const PRICE_MODE_NET = 'NET_MODE';

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return string
     */
    public function havePriceProductStore(PriceProductTransfer $priceProductTransfer): string
    {
        $storeTransfer = $this->haveStore();
        $idCurrency = $this->haveCurrency();

        $priceProductStoreEntity = (new SpyPriceProductStore())
            ->setFkStore($storeTransfer->getIdStoreOrFail())
            ->setFkCurrency($idCurrency)
            ->setFkPriceProduct($priceProductTransfer->getIdPriceProductOrFail())
            ->setGrossPrice(100)
            ->setNetPrice(100);

        $priceProductStoreEntity->save();

        return $priceProductStoreEntity->getIdPriceProductStore();
    }

    /**
     * @param list<int> $idsPriceProductDefault
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefault>
     */
    public function findPriceProductDefaults(array $idsPriceProductDefault): ObjectCollection
    {
        return $this->getPriceProductDefaultQuery()
            ->filterByIdPriceProductDefault_In($idsPriceProductDefault)
            ->find();
    }

    /**
     * @param int $grossAmount
     * @param int $netAmount
     * @param string $skuAbstract
     * @param string $skuConcrete
     * @param string $currencyIsoCode
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function createProductWithAmount(
        int $grossAmount,
        int $netAmount,
        string $skuAbstract = '',
        string $skuConcrete = '',
        string $currencyIsoCode = ''
    ): PriceProductTransfer {
        $priceProductTransfer = (new PriceProductTransfer())
            ->setSkuProductAbstract($skuAbstract)
            ->setSkuProduct($skuConcrete);

        $config = $this->createSharedPriceProductConfig();
        $priceProductDimensionTransfer = (new PriceProductDimensionTransfer())
            ->setType($config->getPriceDimensionDefault());

        $priceProductTransfer->setPriceDimension($priceProductDimensionTransfer);

        if (!$skuAbstract || !$skuConcrete) {
            $priceProductTransfer = $this->buildProduct($priceProductTransfer);
        }

        $storeTransfer = $this->getStoreFacade()->getStoreByName(static::STORE_NAME);
        if ($storeTransfer->getDefaultCurrencyIsoCode() === null && $currencyIsoCode === '') {
            $expandedStoreTransfers = $this->getCurrencyFacade()->expandStoreTransfersWithCurrencies([$storeTransfer]);
            $currencyIsoCode = $expandedStoreTransfers[0]->getDefaultCurrencyIsoCode();
        }

        $currencyTransfer = $this->getCurrencyTransfer($currencyIsoCode ?: $storeTransfer->getDefaultCurrencyIsoCode());

        $moneyValueTransfer = $this->createMoneyValueTransfer(
            $grossAmount,
            $netAmount,
            $storeTransfer,
            $currencyTransfer,
        );

        $priceProductTransfer->setMoneyValue($moneyValueTransfer);

        return $this->getFacade()->createPriceForProduct($priceProductTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\PriceTypeTransfer $priceTypeTransfer
     * @param int $netPrice
     * @param int $grossPrice
     * @param string $currencyIsoCode
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function createPriceProductTransfer(
        ProductConcreteTransfer $productConcreteTransfer,
        PriceTypeTransfer $priceTypeTransfer,
        int $netPrice,
        int $grossPrice,
        string $currencyIsoCode
    ): PriceProductTransfer {
        $config = $this->createSharedPriceProductConfig();
        $priceDimensionTransfer = (new PriceProductDimensionTransfer())
            ->setType($config->getPriceDimensionDefault());

        $priceProductTransfer = (new PriceProductTransfer())
            ->setIdProduct($productConcreteTransfer->getIdProductConcrete())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract())
            ->setSkuProductAbstract($productConcreteTransfer->getAbstractSku())
            ->setSkuProduct($productConcreteTransfer->getSku())
            ->setPriceTypeName($this->getFacade()->getDefaultPriceTypeName())
            ->setPriceType($priceTypeTransfer)
            ->setPriceDimension($priceDimensionTransfer)
            ->setGroupKey('default');

        $currencyTransfer = $this->getCurrencyFacade()->fromIsoCode($currencyIsoCode);
        $storeTransfer = $this->haveStore([StoreTransfer::NAME => static::STORE_NAME]);

        $moneyValueTransfer = $this->createMoneyValueTransfer(
            $grossPrice,
            $netPrice,
            $storeTransfer,
            $currencyTransfer,
        );

        $priceProductTransfer->setMoneyValue($moneyValueTransfer);

        return $priceProductTransfer;
    }

    /**
     * @param string $abstractSku
     * @param int $idAbstractProduct
     * @param string|null $currencyIsoCode
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function createPriceProductForAbstractProduct(
        string $abstractSku,
        int $idAbstractProduct,
        ?string $currencyIsoCode = null
    ): PriceProductTransfer {
        $priceProductTransfer = (new PriceProductTransfer())
            ->setIdProductAbstract($idAbstractProduct)
            ->setSkuProductAbstract($abstractSku)
            ->setPriceDimension(
                (new PriceProductDimensionTransfer())
                    ->setType($this->createSharedPriceProductConfig()->getPriceDimensionDefault()),
            );

        $moneyValueTransfer = $this->createMoneyValueTransfer(
            rand(10, 100),
            rand(1, 9),
            $this->haveStore([StoreTransfer::NAME => static::STORE_NAME]),
            $this->getCurrencyTransfer($currencyIsoCode ?? static::EUR_ISO_CODE),
        );

        $priceProductTransfer->setMoneyValue($moneyValueTransfer);

        return $this->getFacade()->createPriceForProduct($priceProductTransfer);
    }

    /**
     * @param string $abstractSku
     * @param int $idConcreteProduct
     * @param string|null $sku
     * @param string|null $currencyIsoCode
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function createPriceProductForConcreteProduct(
        string $abstractSku,
        int $idConcreteProduct,
        ?string $sku = null,
        ?string $currencyIsoCode = null
    ): PriceProductTransfer {
        $priceProductTransfer = (new PriceProductTransfer())
            ->setIdProduct($idConcreteProduct)
            ->setSkuProductAbstract($abstractSku)
            ->setSkuProduct($sku)
            ->setPriceDimension(
                (new PriceProductDimensionTransfer())
                    ->setType($this->createSharedPriceProductConfig()->getPriceDimensionDefault()),
            );

        $moneyValueTransfer = $this->createMoneyValueTransfer(
            rand(10, 100),
            rand(1, 9),
            $this->haveStore([StoreTransfer::NAME => static::STORE_NAME]),
            $this->getCurrencyTransfer($currencyIsoCode ?? static::EUR_ISO_CODE),
        );

        $priceProductTransfer->setMoneyValue($moneyValueTransfer);

        return $this->getFacade()->createPriceForProduct($priceProductTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductCriteriaTransfer
     */
    public function createPriceProductCriteriaTransfer(): PriceProductCriteriaTransfer
    {
        $config = $this->createSharedPriceProductConfig();
        $priceProductDimensionTransfer = (new PriceProductDimensionTransfer())
            ->setType($config->getPriceDimensionDefault());

        return (new PriceProductCriteriaTransfer())
            ->setPriceDimension($priceProductDimensionTransfer);
    }

    /**
     * @return \Spryker\Zed\Currency\Business\CurrencyFacadeInterface
     */
    public function getCurrencyFacade(): CurrencyFacadeInterface
    {
        return $this->getLocator()->currency()->facade();
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    public function havePriceProductFilterTransfer(): PriceProductFilterTransfer
    {
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setPriceMode(static::PRICE_MODE_NET);

        return $priceProductFilterTransfer;
    }

    /**
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery
     */
    protected function getPriceProductDefaultQuery(): SpyPriceProductDefaultQuery
    {
        return SpyPriceProductDefaultQuery::create();
    }

    /**
     * @return \Spryker\Shared\PriceProduct\PriceProductConfig
     */
    public function createSharedPriceProductConfig(): PriceProductConfig
    {
        return new PriceProductConfig();
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected function getStoreFacade(): StoreFacadeInterface
    {
        return $this->getLocator()->store()->facade();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function buildProduct(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        $productConcreteTransfer = $this->haveProduct();
        $priceProductTransfer
            ->setSkuProductAbstract($productConcreteTransfer->getAbstractSku())
            ->setSkuProduct($productConcreteTransfer->getSku())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract())
            ->setIdProduct($productConcreteTransfer->getIdProductConcrete());

        return $priceProductTransfer;
    }

    /**
     * @param string $currencyIsoCode
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrencyTransfer(string $currencyIsoCode): CurrencyTransfer
    {
        if (!$currencyIsoCode) {
            return $this->getCurrencyFacade()->getDefaultCurrencyForCurrentStore();
        }

        return $this->getCurrencyFacade()->fromIsoCode($currencyIsoCode);
    }

    /**
     * @param int $grossAmount
     * @param int $netAmount
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function createMoneyValueTransfer(
        int $grossAmount,
        int $netAmount,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): MoneyValueTransfer {
        return (new MoneyValueTransfer())
            ->setNetAmount($netAmount)
            ->setGrossAmount($grossAmount)
            ->setFkStore($storeTransfer->getIdStore())
            ->setFkCurrency($currencyTransfer->getIdCurrency())
            ->setCurrency($currencyTransfer);
    }
}
