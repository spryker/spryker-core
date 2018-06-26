<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductListGui\Communication\Form\ProductListForm;
use Spryker\Zed\ProductListGui\Communication\Form\ProductListProductConcreteRelationType;

class ProductConcreteTable extends AbstractTable
{
    protected const TABLE_IDENTIFIER = 'product-table';
    protected const COLUMN_ID = SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT;
    protected const COLUMN_SKU = SpyProductTableMap::COL_SKU;
    protected const COLUMN_NAME = SpyProductLocalizedAttributesTableMap::COL_NAME;
    protected const COLUMN_ACTION = 'action';
    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductListTransfer|null
     */
    protected $productListTransfer;

    /**
     * @var bool
     */
    protected $notInList;

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\ProductListTransfer|null $productListTransfer
     * @param bool $notInList
     */
    public function __construct(
        LocaleTransfer $localeTransfer,
        ?ProductListTransfer $productListTransfer = null,
        bool $notInList = true
    ) {
        $this->localeTransfer = $localeTransfer;
        $this->productListTransfer = $productListTransfer;
        $this->notInList = $notInList;
        $this->setTableIdentifier(self::TABLE_IDENTIFIER);
        if (!$this->notInList) {
            $this->setTableIdentifier(self::TABLE_IDENTIFIER . '-deassigned');
        }
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
        $query = $this->buildQuery();

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

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery
     */
    protected function buildQuery(): SpyProductLocalizedAttributesQuery
    {
        $this->localeTransfer->requireIdLocale();

        $query = (new SpyProductLocalizedAttributesQuery())
            ->innerJoinSpyProduct()
            ->filterByFkLocale($this->localeTransfer->getIdLocale())
            ->select([
                SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT,
                SpyProductTableMap::COL_SKU,
                SpyProductLocalizedAttributesTableMap::COL_NAME,
            ]);

        if ($this->productListTransfer) {
            $this->productListTransfer->requireIdProductList();
            $criteria = $this->notInList ? Criteria::NOT_EQUAL : Criteria::EQUAL;

            /** @var \Orm\Zed\ProductList\Persistence\SpyProductListProductConcreteQuery|\Orm\Zed\Product\Persistence\SpyProductQuery|\Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery $query */
            $query
                ->innerJoinSpyProductListProductConcrete()
                ->filterByFkProductList($this->productListTransfer->getIdProductList(), $criteria);
        }

        return $query;
    }
}
