<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplierDataImport\Business\Model;

use Orm\Zed\Company\Persistence\SpyCompany;
use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Orm\Zed\Currency\Persistence\SpyCurrency;
use Orm\Zed\Currency\Persistence\SpyCurrencyQuery;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\Store\Persistence\SpyStore;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\CompanySupplierDataImport\Business\Model\DataSet\CompanySupplierDataSet;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProduct\Dependency\PriceProductEvents;

class CompanySupplierProductPriceWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    protected const PRICE_TYPE_SUPPLIER = 'SUPPLIER';

    /**
     * @var \Orm\Zed\Currency\Persistence\SpyCurrency[]
     */
    protected static $currencyCache = [];

    /**
     * @var \Orm\Zed\Store\Persistence\SpyStore[]
     */
    protected static $storeCache = [];

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProduct[]
     */
    protected static $productCache = [];

    /**
     * @var \Orm\Zed\Company\Persistence\SpyCompany[]
     */
    protected static $companyCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $priceTypeEntity = SpyPriceTypeQuery::create()
            ->filterByName(static::PRICE_TYPE_SUPPLIER)
            ->findOneOrCreate();

        if ($priceTypeEntity->isNew() || $priceTypeEntity->isModified()) {
            $priceTypeEntity->setPriceModeConfiguration(SpyPriceTypeTableMap::COL_PRICE_MODE_CONFIGURATION_BOTH);
            $priceTypeEntity->save();
        }

        $query = SpyPriceProductQuery::create();
        $query->filterByFkPriceType($priceTypeEntity->getIdPriceType());

        $companyEntity = $this->getCompany($dataSet[CompanySupplierDataSet::COMPANY_NAME]);
        $productEntity = $this->getProduct($dataSet[CompanySupplierDataSet::CONCRETE_SKU]);

        $this->addPublishEvents(PriceProductEvents::PRICE_CONCRETE_PUBLISH, $productEntity->getIdProduct());
        $query->filterByFkProduct($productEntity->getIdProduct());
        $query->filterByFkCompany($companyEntity->getIdCompany());

        $productPriceEntity = $query->findOneOrCreate();
        $productPriceEntity->save();

        $storeEntity = $this->getStore($dataSet[CompanySupplierDataSet::STORE]);
        $currencyEntity = $this->getCurrency($dataSet[CompanySupplierDataSet::CURRENCY]);

        $priceProductStoreEntity = SpyPriceProductStoreQuery::create()
            ->filterByFkStore($storeEntity->getPrimaryKey())
            ->filterByFkCurrency($currencyEntity->getPrimaryKey())
            ->filterByFkPriceProduct($productPriceEntity->getPrimaryKey())
            ->findOneOrCreate();

        $priceProductStoreEntity->setGrossPrice($dataSet[CompanySupplierDataSet::PRICE_GROSS]);
        $priceProductStoreEntity->setNetPrice($dataSet[CompanySupplierDataSet::PRICE_NET]);

        $priceProductStoreEntity->save();
    }

    /**
     * @param string $currencyIsoCode
     *
     * @return \Orm\Zed\Currency\Persistence\SpyCurrency
     */
    protected function getCurrency(string $currencyIsoCode): SpyCurrency
    {
        if (isset(static::$currencyCache[$currencyIsoCode])) {
            return static::$currencyCache[$currencyIsoCode];
        }

        $currencyEntity = SpyCurrencyQuery::create()
            ->filterByCode($currencyIsoCode)
            ->findOne();

        static::$currencyCache[$currencyIsoCode] = $currencyEntity;

        return $currencyEntity;
    }

    /**
     * @param string $storeName
     *
     * @return \Orm\Zed\Store\Persistence\SpyStore
     */
    protected function getStore(string $storeName): SpyStore
    {
        if (isset(static::$storeCache[$storeName])) {
            return static::$storeCache[$storeName];
        }

        $storeEntity = SpyStoreQuery::create()
            ->filterByName($storeName)
            ->findOne();

        static::$storeCache[$storeName] = $storeEntity;

        return $storeEntity;
    }

    /**
     * @param string $productSku
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    protected function getProduct(string $productSku): SpyProduct
    {
        if (isset(static::$productCache[$productSku])) {
            return static::$productCache[$productSku];
        }

        $productEntity = SpyProductQuery::create()
            ->filterBySku($productSku)
            ->findOne();

        static::$productCache[$productSku] = $productEntity;

        return $productEntity;
    }

    /**
     * @param string $companyName
     *
     * @return \Orm\Zed\Company\Persistence\SpyCompany
     */
    protected function getCompany(string $companyName): SpyCompany
    {
        if (isset(static::$companyCache[$companyName])) {
            return static::$companyCache[$companyName];
        }

        $companyEntity = SpyCompanyQuery::create()
            ->filterByName($companyName)
            ->findOne();

        static::$companyCache[$companyName] = $companyEntity;

        return $companyEntity;
    }
}
