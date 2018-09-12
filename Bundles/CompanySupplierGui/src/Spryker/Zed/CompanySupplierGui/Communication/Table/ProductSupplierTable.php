<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplierGui\Communication\Table;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToCurrencyFacadeInterface;
use Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToMoneyFacadeInterface;
use Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToStoreFacadeInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class ProductSupplierTable extends AbstractTable
{
    protected const TABLE_IDENTIFIER = 'product-suppliers-table';
    protected const COL_SKU = 'sku';
    protected const COL_SUPPLIER_PRICE = 'supplier_price';
    protected const COL_DEFAULT_PRICE = 'default_price';
    protected const PRICE_FORMAT = '%s: %s%s';
    protected const TABLE_URL_DEFAULT_FORMAT = 'table?%s=%d';
    protected const PRICE_SEPARATOR = '<br/>';
    protected const PRICE_TYPE_SUPPLIER = 'SUPPLIER';
    protected const PRICE_TYPE_DEFAULT = 'DEFAULT';
    protected const PARAM_ID_COMPANY = 'id-company';

    /**
     * @var int
     */
    protected $idCompany;

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected $productQuery;

    /**
     * @var \Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @var \Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @param int $idCompany
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productQuery
     * @param \Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToMoneyFacadeInterface $moneyFacade
     * @param \Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToCurrencyFacadeInterface $currencyFacade
     */
    public function __construct(
        int $idCompany,
        SpyProductQuery $productQuery,
        CompanySupplierGuiToMoneyFacadeInterface $moneyFacade,
        CompanySupplierGuiToStoreFacadeInterface $storeFacade,
        CompanySupplierGuiToCurrencyFacadeInterface $currencyFacade
    ) {
        $this->setTableIdentifier(static::TABLE_IDENTIFIER);
        $this->idCompany = $idCompany;
        $this->productQuery = $productQuery;
        $this->moneyFacade = $moneyFacade;
        $this->storeFacade = $storeFacade;
        $this->currencyFacade = $currencyFacade;

        $this->defaultUrl = sprintf(
            static::TABLE_URL_DEFAULT_FORMAT,
            static::PARAM_ID_COMPANY,
            $idCompany
        );
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COL_SKU => 'SKU',
            static::COL_SUPPLIER_PRICE => 'Purchasing price',
            static::COL_DEFAULT_PRICE => 'Customer price',
        ]);

        $config->setRawColumns([
            static::COL_SUPPLIER_PRICE,
            static::COL_DEFAULT_PRICE,
        ]);

        $config->setSearchable([
            static::COL_SKU,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $productSupplierCollection = $this->runQuery(
            $this->prepareQuery(),
            $config,
            true
        );

        if ($productSupplierCollection->count() < 1) {
            return [];
        }

        return $this->format($productSupplierCollection);
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function prepareQuery(): SpyProductQuery
    {
        $query = $this->productQuery
            ->rightJoinSpyCompanySupplierToProduct()
            ->useSpyCompanySupplierToProductQuery()
                ->filterByFkCompany($this->idCompany)
            ->endUse();

        return $query;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $spyProductCollection
     *
     * @return array
     */
    public function format(ObjectCollection $spyProductCollection): array
    {
        $productSuppliers = [];
        /** @var \Orm\Zed\Product\Persistence\SpyProduct $item */
        foreach ($spyProductCollection as $spyProductEntity) {
            $productTransfer = new ProductConcreteTransfer();
            $productTransfer->fromArray($spyProductEntity->toArray(), true);
            $this->setProductSupplierPrices($spyProductEntity, $productTransfer);
            $productSuppliers[] = $productTransfer->toArray();
        }

        return $productSuppliers;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $spyProductEntity
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     *
     * @return void
     */
    protected function setProductSupplierPrices(SpyProduct $spyProductEntity, ProductConcreteTransfer &$productTransfer): void
    {
        $productTransfer->setSupplierPrice($this->getSupplierPrice($spyProductEntity));
        $productTransfer->setDefaultPrice($this->getDefaultPrice($spyProductEntity));
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $spyProductEntity
     *
     * @return string
     */
    protected function getSupplierPrice(SpyProduct $spyProductEntity): string
    {
        $criteria = new Criteria();
        $criteria->add(SpyPriceTypeTableMap::COL_NAME, static::PRICE_TYPE_SUPPLIER);
        $criteria->add(SpyPriceProductTableMap::COL_FK_COMPANY, $this->idCompany);

        $prices = $spyProductEntity->getPriceProductsJoinPriceType($criteria);
        if ($prices->count() < 1) {
            return '';
        }

        return $this->formatPrices($prices[0]->getPriceProductStoresJoinCurrency());
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $spyProductEntity
     *
     * @return string
     */
    protected function getDefaultPrice(SpyProduct $spyProductEntity): string
    {
        $criteria = new Criteria();
        $criteria->add(SpyPriceTypeTableMap::COL_NAME, static::PRICE_TYPE_DEFAULT);

        $prices = $spyProductEntity->getPriceProductsJoinPriceType($criteria);
        if ($prices->count() > 0) {
            return $this->formatPrices($prices[0]->getPriceProductStoresJoinCurrency());
        }
        $abstractPrices = $spyProductEntity->getSpyProductAbstract()->getPriceProductsJoinPriceType($criteria);
        if ($abstractPrices->count() > 0) {
            return $this->formatPrices($abstractPrices[0]->getPriceProductStoresJoinCurrency());
        }

        return '';
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $priceProductCollection
     *
     * @return string
     */
    protected function formatPrices(ObjectCollection $priceProductCollection): string
    {
        $prices = [];
        if ($priceProductCollection->count() > 0) {
            /** @var \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore $priceProductEntity */
            foreach ($priceProductCollection as $priceProductEntity) {
                $prices[] = sprintf(
                    static::PRICE_FORMAT,
                    $this->storeFacade->getStoreById($priceProductEntity->getFkStore())->getName(),
                    $this->currencyFacade->getByIdCurrency($priceProductEntity->getFkCurrency())->getSymbol(),
                    $this->moneyFacade->convertIntegerToDecimal($priceProductEntity->getGrossPrice())
                );
            }
        }

        return implode(static::PRICE_SEPARATOR, $prices);
    }
}
