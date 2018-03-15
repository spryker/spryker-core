<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplierGui\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\ProductBundle\Persistence\Map\SpyProductBundleTableMap;
use Orm\Zed\Stock\Persistence\Map\SpyStockProductTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\CompanySupplier\Persistence\CompanySupplierQueryContainerInterface;
use Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToCompanySupplierFacadeInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Money\Business\MoneyFacadeInterface;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToAvailabilityInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToMoneyInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceProductInterface;
use Spryker\Zed\ProductManagement\Dependency\Service\ProductManagementToUtilEncodingInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

class ProductSupplierTable extends AbstractTable
{
    const COL_SKU = 'sku';
    const COL_SUPPLIER_PRICE = 'supplier_price';
    const COL_DEFAULT_PRICE = 'default_price';
    protected const PRICE_FORMAT = '%s: %s';

    protected const PRICE_SEPARATOR = '<br/>';

    public const PRICE_TYPE_SUPPLIER = 'SUPPLIER';

    public const PRICE_TYPE_DEFAULT = 'DEFAULT';

    /**
     * @var CompanySupplierGuiToCompanySupplierFacadeInterface
     */
    protected $companySupplierFacade;

    protected $companySupplierQueryContainer;

    protected $moneyFacade;

    protected $storeFacade;


    /**
     * @param CompanySupplierGuiToCompanySupplierFacadeInterface $companySupplierFacade
     */
    public function __construct(
        CompanySupplierGuiToCompanySupplierFacadeInterface $companySupplierFacade,
        CompanySupplierQueryContainerInterface $companySupplierQueryContainer,
        MoneyFacadeInterface $moneyFacade,
        StoreFacadeInterface $storeFacade
    )
    {
        $this->setTableIdentifier('product-suppliers-table');
        $this->companySupplierFacade = $companySupplierFacade;
        $this->companySupplierQueryContainer = $companySupplierQueryContainer;
        $this->moneyFacade = $moneyFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
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
            static::COL_SKU
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
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

//        $productSupplierCollection = $this->companySupplierFacade->getAllProductSupplements();
//
//        $tableData = [];
//        /** @var ProductConcreteTransfer $productSupplier */
//        foreach ($productSupplierCollection->getProducts() as $productSupplier) {
//            $tableData[] = [
//                static::COL_SKU => $productSupplier->getSku(),
//                static::COL_SUPPLIER_PRICE => $productSupplier->getSupplierPrice(),
//                static::COL_DEFAULT_PRICE => $productSupplier->getDefaultPrice(),
//            ];
//        }
//
//        return $tableData;
    }

    protected function prepareQuery()
    {
        return $this->companySupplierQueryContainer->queryAProductSuppliers();
    }

    public function format(ObjectCollection $spyProductCollection)
    {
        $productSuppliers = [];
        /** @var SpyProduct $item */
        foreach ($spyProductCollection as $spyProductEntity) {
            $productTransfer = new ProductConcreteTransfer();
            $productTransfer->fromArray($spyProductEntity->toArray(), true);
            $this->setSupplierPrices($spyProductEntity,$productTransfer);
            $productSuppliers[] = $productTransfer->toArray();
        }

        return $productSuppliers;
    }

    protected function setSupplierPrices(SpyProduct $spyProductEntity, ProductConcreteTransfer &$productTransfer)
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

    protected function formatPrices(ObjectCollection $priceProductCollection)
    {
        $prices = [];
        if ($priceProductCollection->count() > 0) {
            /** @var \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore $priceProductEntity */
            foreach ($priceProductCollection as $priceProductEntity) {
                $prices[] = sprintf(
                    static::PRICE_FORMAT,
                    $this->storeFacade->getStoreById($priceProductEntity->getFkStore())->getName(),
                    $this->moneyFacade->convertIntegerToDecimal($priceProductEntity->getGrossPrice())
                );
            }
        }

        return implode(static::PRICE_SEPARATOR, $prices);
    }
}
