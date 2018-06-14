<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Table;

use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductListGui\Communication\Form\ProductListForm;
use Spryker\Zed\ProductListGui\Communication\Form\ProductListProductConcreteRelationType;

class ProductConcreteTable extends AbstractTable
{
    const TABLE_IDENTIFIER = 'product-table';
    protected const COLUMN_ID = SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT;
    protected const COLUMN_SKU = SpyProductTableMap::COL_SKU;
    protected const COLUMN_NAME = SpyProductLocalizedAttributesTableMap::COL_NAME;
    protected const COLUMN_ACTION = 'action';

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
     */
    public function __construct()
    {
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COLUMN_ID => 'ID',
            static::COLUMN_SKU => 'SKU',
            static::COLUMN_NAME => 'Name',
            static::COLUMN_ACTION => 'Selected',
        ]);

        $config->setSearchable([
            static::COLUMN_ID,
            static::COLUMN_SKU,
            static::COLUMN_NAME,
        ]);

        $config->setSortable([
            static::COLUMN_ID,
            static::COLUMN_SKU,
            static::COLUMN_NAME,
        ]);

        $config->addRawColumn(self::COLUMN_ACTION);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = (new SpyProductLocalizedAttributesQuery())
            ->leftJoinSpyProduct()
            ->filterByFkLocale(66)
            ->select([
                SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT,
                SpyProductTableMap::COL_SKU,
                SpyProductLocalizedAttributesTableMap::COL_NAME,
            ]);

        $queryResults = $this->runQuery($query, $config);

        $results = [];
        foreach ($queryResults as $product) {
            $results[] = $this->buildDataRow($product);
        }
        unset($queryResults);

        return $results;
    }

    /**
     * @param string[] $product
     *
     * @return string[]
     */
    protected function buildDataRow(array $product): array
    {
        $checkboxName = sprintf(
            '%s[%s][%s][]',
            ProductListForm::BLOCK_PREFIX,
            ProductListForm::FIELD_PRODUCTS,
            ProductListProductConcreteRelationType::FIELD_PRODUCTS
        );
        $idProduct = $product[SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT];

        return [
            static::COLUMN_ID => $idProduct,
            static::COLUMN_SKU => $product[SpyProductTableMap::COL_SKU],
            static::COLUMN_NAME => $product[SpyProductLocalizedAttributesTableMap::COL_NAME],
            static::COLUMN_ACTION => sprintf(
                "<input class='all-products-checkbox' type='checkbox' name='%s' value='%d'>",
                $checkboxName,
                $idProduct
            ),
        ];
    }
}
