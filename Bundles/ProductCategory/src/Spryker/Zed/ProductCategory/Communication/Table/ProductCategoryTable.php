<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductCategory\Dependency\Service\ProductCategoryToUtilEncodingInterface;
use Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface;

class ProductCategoryTable extends AbstractTable
{
    public const TABLE_IDENTIFIER = 'product-category-table';
    public const COL_CHECKBOX = 'checkbox';
    public const PARAM_ID_CATEGORY = 'id-category';

    /**
     * @var \Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface
     */
    protected $productCategoryQueryContainer;

    /**
     * @var \Spryker\Zed\ProductCategory\Dependency\Service\ProductCategoryToUtilEncodingInterface
     */
    protected $utilEncodingService;

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
     * @param \Spryker\Zed\ProductCategory\Dependency\Service\ProductCategoryToUtilEncodingInterface $utilEncodingService
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param int $idCategory
     */
    public function __construct(
        ProductCategoryQueryContainerInterface $productCategoryQueryContainer,
        ProductCategoryToUtilEncodingInterface $utilEncodingService,
        LocaleTransfer $locale,
        $idCategory
    ) {
        $this->productCategoryQueryContainer = $productCategoryQueryContainer;
        $this->utilEncodingService = $utilEncodingService;
        $this->locale = $locale;
        $this->idCategory = $idCategory;
        $this->defaultUrl = sprintf('product-category-table?%s=%d', static::PARAM_ID_CATEGORY, $this->idCategory);
        $this->setTableIdentifier(static::TABLE_IDENTIFIER);
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
            SpyProductCategoryTableMap::COL_PRODUCT_ORDER => 'Order',
            static::COL_CHECKBOX => 'Selected',
        ]);
        $config->setSearchable([
            SpyProductAbstractTableMap::COL_SKU,
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
        ]);
        $config->setSortable([
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
            SpyProductAbstractTableMap::COL_SKU,
            SpyProductCategoryTableMap::COL_PRODUCT_ORDER,
        ]);

        $config->addRawColumn(SpyProductCategoryTableMap::COL_PRODUCT_ORDER);
        $config->addRawColumn(static::COL_CHECKBOX);
        $config->setDefaultSortField(SpyProductCategoryTableMap::COL_PRODUCT_ORDER, TableConfiguration::SORT_ASC);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->productCategoryQueryContainer->queryProductsByCategoryId($this->idCategory, $this->locale);
        $query->clearOrderByColumns();
        $query->setModelAlias('spy_product_abstract');

        $queryResults = $this->runQuery($query, $config);

        $results = [];
        foreach ($queryResults as $productCategory) {
            $results[] = [
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT => $productCategory['id_product_abstract'],
                SpyProductAbstractTableMap::COL_SKU => $productCategory['sku'],
                SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => $productCategory['name'],
                SpyProductCategoryTableMap::COL_PRODUCT_ORDER => $this->getOrderHtml($productCategory),
                static::COL_CHECKBOX => $this->getCheckboxHtml($productCategory),
            ];
        }
        unset($queryResults);

        return $results;
    }

    /**
     * @param array $productCategory
     *
     * @return string
     */
    protected function getCheckboxHtml(array $productCategory)
    {
        $info = [
            'id' => $productCategory['id_product_abstract'],
            'sku' => $productCategory['sku'],
            'name' => urlencode($productCategory['name']),
        ];

        return sprintf(
            "<input id='product_category_checkbox_%d' class='product_category_checkbox' type='checkbox' checked='checked' data-info='%s'>",
            $productCategory['id_product_abstract'],
            $this->utilEncodingService->encodeJson($info)
        );
    }

    /**
     * @param array $productCategory
     *
     * @return string
     */
    protected function getOrderHtml(array $productCategory)
    {
        $info = [
            'id' => $productCategory['id_product_abstract'],
        ];

        return sprintf(
            "<input type='text' value='%d' id='product_category_order_%d' class='product_category_order' size='4' data-info='%s'>",
            $productCategory['product_order'],
            $productCategory['id_product_abstract'],
            $this->utilEncodingService->encodeJson($info)
        );
    }
}
