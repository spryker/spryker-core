<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerFeature\Zed\ProductCategory\ProductCategoryConfig;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyLocalizedAbstractProductAttributesTableMap;

class ProductTable extends AbstractTable
{

    const TABLE_IDENTIFIER = 'product-table';

    /**
     * @var ProductCategoryQueryContainerInterface
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
     * @param ProductCategoryQueryContainerInterface $productCategoryQueryContainer
     * @param LocaleTransfer $locale
     * @param int $idCategory
     */
    public function __construct(ProductCategoryQueryContainerInterface $productCategoryQueryContainer, LocaleTransfer $locale, $idCategory)
    {
        $this->productCategoryQueryContainer = $productCategoryQueryContainer;
        $this->locale = $locale;
        $this->idCategory = (int) $idCategory;
        $this->defaultUrl = sprintf('productTable?%s=%d', ProductCategoryConfig::PARAM_ID_CATEGORY, $this->idCategory);
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
            'checkbox' => 'Selected',

        ]);
        $config->setSortable([
            SpyAbstractProductTableMap::COL_SKU,
            SpyLocalizedAbstractProductAttributesTableMap::COL_NAME,
        ]);

        $config->setPageLength(10);

        return $config;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->productCategoryQueryContainer->queryAbstractProductsBySearchTerm(null, $this->locale, $this->idCategory);
        $query->setModelAlias('spy_abstract_product');

        $queryResults = $this->runQuery($query, $config);

        $results = [];
        foreach ($queryResults as $product) {
            $results[] = [
                SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT => $product[SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT],
                SpyAbstractProductTableMap::COL_SKU => $product[SpyAbstractProductTableMap::COL_SKU],
                SpyLocalizedAbstractProductAttributesTableMap::COL_NAME => $product['name'],
                'checkbox' => '<input id="all_products_checkbox_' .
                    $product[SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT] .
                    '" type="checkbox" onclick="allProductsClickMarkAsSelected(this.checked, ' .
                    $product[SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT] .
                    ', \'' . $product[SpyAbstractProductTableMap::COL_SKU] . '\', \'' .
                    urlencode($product['name']) . '\'); return" /> ',
            ];
        }
        unset($queryResults);

        return $results;
    }

}
