<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplierGui\Communication\Table;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToCurrencyFacadeInterface;
use Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToMoneyFacadeInterface;
use Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToStoreFacadeInterface;
use Spryker\Zed\CompanySupplierGui\Dependency\QueryContainer\CompanySupplierGuiToCompanySupplierQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class ProductSupplierTable extends AbstractTable
{
    protected const COL_SKU = 'sku';
    protected const COL_SUPPLIER_PRICE = 'supplier_price';
    protected const COL_DEFAULT_PRICE = 'default_price';
    protected const PRICE_FORMAT = '%s: %s%s';
    protected const PRICE_SEPARATOR = '<br/>';
    protected const PRICE_TYPE_SUPPLIER = 'SUPPLIER';
    protected const PRICE_TYPE_DEFAULT = 'DEFAULT';

    /** @var \Spryker\Zed\CompanySupplierGui\Dependency\QueryContainer\CompanySupplierGuiToCompanySupplierQueryContainerInterface */
    protected $companySupplierQueryContainer;

    /** @var \Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToMoneyFacadeInterface */
    protected $moneyFacade;

    /** @var \Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToStoreFacadeInterface */
    protected $storeFacade;

    /** @var \Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToCurrencyFacadeInterface */
    protected $currencyFacade;

    /**
     * @param \Spryker\Zed\CompanySupplierGui\Dependency\QueryContainer\CompanySupplierGuiToCompanySupplierQueryContainerInterface $companySupplierQueryContainer
     * @param \Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToMoneyFacadeInterface $moneyFacade
     * @param \Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToCurrencyFacadeInterface $currencyFacade
     */
    public function __construct(
        CompanySupplierGuiToCompanySupplierQueryContainerInterface $companySupplierQueryContainer,
        CompanySupplierGuiToMoneyFacadeInterface $moneyFacade,
        CompanySupplierGuiToStoreFacadeInterface $storeFacade,
        CompanySupplierGuiToCurrencyFacadeInterface $currencyFacade
    ) {
        $this->setTableIdentifier('product-suppliers-table');
        $this->companySupplierQueryContainer = $companySupplierQueryContainer;
        $this->moneyFacade = $moneyFacade;
        $this->storeFacade = $storeFacade;
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setUrl('table');

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
        return $this->companySupplierQueryContainer->queryProductSuppliers();
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
        $productTransfer->setSupplierPrice('');
        $productTransfer->setDefaultPrice('');
        if ($spyProductEntity->getPriceProductsJoinPriceType()->count() > 0) {
            foreach ($spyProductEntity->getPriceProductsJoinPriceType() as $priceProduct) {
                if ($priceProduct->getPriceType()->getName() === static::PRICE_TYPE_SUPPLIER) {
                    $productTransfer->setSupplierPrice(
                        $this->formatPrices($priceProduct->getPriceProductStores())
                    );

                    continue;
                }
                if ($priceProduct->getPriceType()->getName() === static::PRICE_TYPE_DEFAULT) {
                    $productTransfer->setDefaultPrice(
                        $this->formatPrices($priceProduct->getPriceProductStores())
                    );

                    continue;
                }
            }
        }
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
