<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerFeature\Zed\ProductCategory\ProductCategoryConfig;
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
        $this->defaultUrl = sprintf('productCategoryTable?%s=%d', ProductCategoryConfig::PARAM_ID_CATEGORY, $this->idCategory);
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
            SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT => 'ID',
            SpyAbstractProductTableMap::COL_SKU => 'SKU',
            SpyLocalizedAbstractProductAttributesTableMap::COL_NAME => 'Name',
            SpyProductCategoryTableMap::COL_FK_PRECONFIG_PRODUCT => 'Preconfig',
            'checkbox' => 'Selected',
        ]);
        $config->setSortable([
            SpyAbstractProductTableMap::COL_SKU,
            SpyLocalizedAbstractProductAttributesTableMap::COL_NAME,
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
                SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT => $productCategory['id_abstract_product'],
                SpyAbstractProductTableMap::COL_SKU => $productCategory['sku'],
                SpyLocalizedAbstractProductAttributesTableMap::COL_NAME => $productCategory['name'],
                SpyProductCategoryTableMap::COL_FK_PRECONFIG_PRODUCT => $productCategory[SpyProductCategoryTableMap::COL_FK_PRECONFIG_PRODUCT],
                'checkbox' => '<input id="product_category_checkbox_' .
                    $productCategory['id_abstract_product'] .
                    '" type="checkbox" checked="checked" onclick="categoryTableClickMarkAsSelected(this, ' .
                    $productCategory['id_abstract_product'] . ', \'' .
                    $productCategory['sku'] . '\', \'' .
                    urlencode($productCategory['name']) . '\'); return" /> ',
            ];
        }
        unset($queryResults);
        return $results;
    }
}
