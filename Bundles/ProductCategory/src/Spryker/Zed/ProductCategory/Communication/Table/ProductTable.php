<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Spryker\Shared\ProductCategory\ProductCategoryConstants;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface;

class ProductTable extends AbstractTable
{

    const TABLE_IDENTIFIER = 'product-table';

    /**
     * @var \Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface
     */
    protected $productCategoryQueryContainer;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $locale;

    /**
     * @var int
     */
    protected $idCategory;

    /**
     * @param \Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface $productCategoryQueryContainer
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param int $idCategory
     */
    public function __construct(ProductCategoryQueryContainerInterface $productCategoryQueryContainer, LocaleTransfer $locale, $idCategory)
    {
        $this->productCategoryQueryContainer = $productCategoryQueryContainer;
        $this->locale = $locale;
        $this->idCategory = (int)$idCategory;
        $this->defaultUrl = sprintf('product-table?%s=%d', ProductCategoryConstants::PARAM_ID_CATEGORY, $this->idCategory);
        $this->setTableIdentifier(self::TABLE_IDENTIFIER);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
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
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->productCategoryQueryContainer->queryProductsAbstractBySearchTerm(null, $this->locale);
        $query->setModelAlias('spy_product_abstract');

        $queryResults = $this->runQuery($query, $config);

        $results = [];
        foreach ($queryResults as $product) {
            $checkbox_html = sprintf(
                "<input id='all_products_checkbox_%d' class='all-products-checkbox' type='checkbox' 
                data-info='{\"id\": %d, \"sku\": \"%s\", \"name\": \"%s\"}'>",
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
