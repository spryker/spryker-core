<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerFeature\Zed\Category\CategoryConfig;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyLocalizedAbstractProductAttributesTableMap;
use SprykerFeature\Zed\ProductCategory\Persistence\ProductCategoryQueryContainer;
use SprykerFeature\Zed\ProductCategory\Persistence\Propel\Map\SpyProductCategoryTableMap;


class ProductCategoryTable extends AbstractTable
{
    const TABLE_IDENTIFIER = 'product-category-table';

    /**
     * @var ProductCategoryQueryContainer
     */
    protected $productCategoryQueryContainer;


    /**
     * @var LocaleTransfer
     */
    protected $locale;

    /**
     * @var int
     */
    protected $idCategory;

    /**
     * @param ProductCategoryQueryContainer $productCategoryQueryContainer
     * @param LocaleTransfer $locale
     * @param int $idCategory
     */
    public function __construct(ProductCategoryQueryContainer $productCategoryQueryContainer, LocaleTransfer $locale, $idCategory)
    {
        $this->productCategoryQueryContainer = $productCategoryQueryContainer;
        $this->locale = $locale;
        $this->idCategory = $idCategory;
        $this->defaultUrl = sprintf('productCategoryTable?%s=%d', CategoryConfig::PARAM_ID_CATEGORY, $this->idCategory);
        $this->setTableIdentifier(self::TABLE_IDENTIFIER);
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            //'checkboxes' => 'Select All',
            SpyAbstractProductTableMap::COL_SKU => 'SKU',
            SpyLocalizedAbstractProductAttributesTableMap::COL_NAME => 'Name',
            SpyProductCategoryTableMap::COL_FK_PRECONFIG_PRODUCT => 'Preconfig',

        ]);
        $config->setSortable([
            SpyAbstractProductTableMap::COL_SKU,
            //SpyLocalizedAbstractProductAttributesTableMap::COL_NAME,
        ]);

        return $config;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->productCategoryQueryContainer->queryProductsByCategoryId($this->idCategory, $this->locale);
        $query->setModelAlias('spy_abstract_product');
        
        $queryResults = $this->runQuery($query, $config);
        
        $results = [];
        foreach ($queryResults as $productCategory) {
            $results[] = [
                SpyAbstractProductTableMap::COL_SKU => '<input type="checkbox" /> '.$productCategory['sku'],
                SpyLocalizedAbstractProductAttributesTableMap::COL_NAME => $productCategory['name'],
                SpyProductCategoryTableMap::COL_FK_PRECONFIG_PRODUCT => $productCategory[SpyProductCategoryTableMap::COL_FK_PRECONFIG_PRODUCT],
                //'checkboxes' => '<input type="checkbox" />',
            ];
        }
        unset($queryResults);
        return $results;
    }
}
