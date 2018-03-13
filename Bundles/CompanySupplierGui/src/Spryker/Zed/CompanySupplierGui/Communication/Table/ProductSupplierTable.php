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
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToCompanySupplierFacadeInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToAvailabilityInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToMoneyInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceProductInterface;
use Spryker\Zed\ProductManagement\Dependency\Service\ProductManagementToUtilEncodingInterface;

class ProductSupplierTable extends AbstractTable
{
    const COL_SKU = 'sku';
    const COL_NAME = 'name';
    const COL_PRICE = 'price';
    const COL_STOCK = 'stocks';
    const COL_DISCOUNT = 'discount';
    const COL_WAREHOUSE = 'warehouse';

    /**
     * @var CompanySupplierGuiToCompanySupplierFacadeInterface
     */
    protected $companySupplierFacade;


    /**
     * @param CompanySupplierGuiToCompanySupplierFacadeInterface $companySupplierFacade
     */
    public function __construct(
        CompanySupplierGuiToCompanySupplierFacadeInterface $companySupplierFacade
    ) {
        $this->setTableIdentifier('product-suppliers-table');
        $this->companySupplierFacade = $companySupplierFacade;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
//        $config->setUrl(
//            sprintf(
//                'bundledProductTable?id-product-concrete=%d',
//                $this->idProductConcrete
//            )
//        );
//
//        $defaultPriceMode = $this->priceFacade->getDefaultPriceMode();
//
//        $priceLabel = sprintf('Price (%s)', $defaultPriceMode);

        $config->setHeader([
            static::COL_SKU => 'SKU',
            static::COL_NAME => 'Name',
            static::COL_PRICE => 'Purchasing price',
            static::COL_STOCK => 'Internal Warehouses',
            static::COL_DISCOUNT => 'Discount (in %)',
            static::COL_WAREHOUSE => 'External Warehouses',

        ]);

        $config->setRawColumns([
            static::COL_STOCK,
            static::COL_DISCOUNT,
            static::COL_WAREHOUSE,
        ]);

        $config->setSearchable([
//            static::COL_SKU => 'SKU',
//            static::COL_NAME => 'Name',
        ]);
//
        $config->setSortable([
//            SpyProductLocalizedAttributesTableMap::COL_NAME,
//            SpyProductTableMap::COL_SKU,
//            SpyStockProductTableMap::COL_QUANTITY,
//            SpyStockProductTableMap::COL_IS_NEVER_OUT_OF_STOCK,
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
        $productSupplierCollection = $this->companySupplierFacade->getAllProductSupplements();

        $tableData = [];
        /** @var ProductConcreteTransfer $productSupplier */
        foreach ($productSupplierCollection as $productSupplier) {
            $tableData[] = [
                static::COL_SKU => $productSupplier->getSku(),
                static::COL_NAME => 'test',
                static::COL_PRICE => '1000',
                static::COL_STOCK => $productSupplier->getStocks()[0]->getQuantity(),
                static::COL_DISCOUNT => '100',
                static::COL_WAREHOUSE => '100',
            ];
        }

//        $query = $this
//            ->productQueryContainer
//            ->queryProduct()
//            ->leftJoinSpyProductBundleRelatedByFkProduct()
//            ->joinSpyProductLocalizedAttributes()
//            ->joinStockProduct()
//            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_NAME, self::SPY_PRODUCT_LOCALIZED_ATTRIBUTE_ALIAS_NAME)
//            ->withColumn(sprintf('SUM(%s)', SpyStockProductTableMap::COL_QUANTITY), 'stockQuantity')
//            ->withColumn(SpyStockProductTableMap::COL_IS_NEVER_OUT_OF_STOCK, self::IS_NEVER_OUT_OF_STOCK)
//            ->where(SpyProductLocalizedAttributesTableMap::COL_FK_LOCALE . ' = ?', $this->localeTransfer->getIdLocale())
//            ->add(SpyProductBundleTableMap::COL_ID_PRODUCT_BUNDLE, null, Criteria::ISNULL)
//            ->groupBy(SpyProductTableMap::COL_ID_PRODUCT)
//            ->addGroupByColumn(self::SPY_PRODUCT_LOCALIZED_ATTRIBUTE_ALIAS_NAME)
//            ->addGroupByColumn(self::IS_NEVER_OUT_OF_STOCK);
//
//        $queryResults = $this->runQuery($query, $config, true);
//
//        $productAbstractCollection = [];
//        foreach ($queryResults as $item) {
//            $productAbstractCollection[] = [
//                static::COL_SELECT => $this->addCheckBox($item),
//                static::COL_ID_PRODUCT_CONCRETE => $item->getIdProduct(),
//                SpyProductLocalizedAttributesTableMap::COL_NAME => $item->getName(),
//                SpyProductTableMap::COL_SKU => $this->getProductEditPageLink($item->getSku(), $item->getFkProductAbstract(), $item->getIdProduct()),
//                static::COL_PRICE => $this->getFormattedPrice($item->getSku()),
//                SpyStockProductTableMap::COL_QUANTITY => $item->getStockQuantity(),
//                static::COL_AVAILABILITY => $this->getAvailability($item),
//                SpyStockProductTableMap::COL_IS_NEVER_OUT_OF_STOCK => $item->getIsNeverOutOfStock(),
//            ];
//        }
//
//        return $productAbstractCollection;

        return $tableData;
    }

    /**
     * @param string $sku
     * @param int $idProductAbstract
     * @param int $idProductConcrete
     *
     * @return string
     */
//    protected function getProductEditPageLink($sku, $idProductAbstract, $idProductConcrete)
//    {
//        $pageEditUrl = Url::generate('/product-management/edit/variant', [
//            'id-product-abstract' => $idProductAbstract,
//            'id-product' => $idProductConcrete,
//        ])->build();
//
//        $pageEditLink = '<a target="_blank" href="' . $pageEditUrl . '">' . $sku . '</a>';
//
//        return $pageEditLink;
//    }

    /**
     * @param string $sku
     *
     * @return string
     */
//    protected function getFormattedPrice($sku)
//    {
//        $priceInCents = $this->priceProductFacade->findPriceBySku($sku);
//
//        if ($priceInCents === null) {
//            return 'N/A';
//        }
//
//        $moneyTransfer = $this->moneyFacade->fromInteger($priceInCents);
//
//        return $this->moneyFacade->formatWithSymbol($moneyTransfer);
//    }
//
//    /**
//     * @param \Orm\Zed\Product\Persistence\SpyProduct $productConcreteEntity
//     *
//     * @return string
//     */
//    protected function addCheckBox(SpyProduct $productConcreteEntity)
//    {
//        $checked = '';
//        if ($this->idProductConcrete) {
//            $criteria = new Criteria();
//            $criteria->add(SpyProductBundleTableMap::COL_FK_PRODUCT, $this->idProductConcrete);
//
//            if ($productConcreteEntity->getSpyProductBundlesRelatedByFkBundledProduct($criteria)->count() > 0) {
//                $checked = 'checked="checked"';
//            }
//        }
//
//        return sprintf(
//            "<input id='product_assign_checkbox_%d' class='product_assign_checkbox' type='checkbox' data-info='%s' %s >",
//            $productConcreteEntity->getIdProduct(),
//            $this->utilEncodingService->encodeJson($productConcreteEntity->toArray()),
//            $checked
//        );
//    }
//
//    /**
//     * @param \Orm\Zed\Product\Persistence\SpyProduct $productConcreteEntity
//     *
//     * @return int
//     */
//    protected function getAvailability(SpyProduct $productConcreteEntity)
//    {
//        $availability = 0;
//        if (!$productConcreteEntity->getIsNeverOutOfStock()) {
//            $availability = $this->availabilityFacade->calculateStockForProduct($productConcreteEntity->getSku());
//        }
//        return $availability;
//    }
}
