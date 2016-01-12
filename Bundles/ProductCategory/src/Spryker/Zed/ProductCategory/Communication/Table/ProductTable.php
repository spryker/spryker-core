<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCategory\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\ProductCategory\ProductCategoryConfig;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;

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
        $this->defaultUrl = sprintf('product-table?%s=%d', ProductCategoryConfig::PARAM_ID_CATEGORY, $this->idCategory);
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
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT => 'ID',
            SpyProductAbstractTableMap::COL_SKU => 'SKU',
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => 'Name',
            'checkbox' => 'Selected',
        ]);

        $config->setSearchable([
            SpyProductAbstractTableMap::COL_SKU,
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
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
        $query = $this->productCategoryQueryContainer->queryProductAbstractCollectionBySearchTerm(null, $this->locale, $this->idCategory);
        $query->setModelAlias('spy_product_abstract');

        $queryResults = $this->runQuery($query, $config);

        $results = [];
        foreach ($queryResults as $product) {
            $checkbox_html = sprintf(
                '<input id="all_products_checkbox_%d" type="checkbox" onclick="allProductsClickMarkAsSelected(this.checked, %d, \'%s\', \'%s\'); return" /> ',
                $product[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT],
                $product[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT],
                $product[SpyProductAbstractTableMap::COL_SKU],
                urlencode($product['name'])
            );

            $results[] = [
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT => $product[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT],
                SpyProductAbstractTableMap::COL_SKU => $product[SpyProductAbstractTableMap::COL_SKU],
                SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => $product['name'],
                'checkbox' => $checkbox_html,
            ];
        }
        unset($queryResults);

        return $results;
    }

}
