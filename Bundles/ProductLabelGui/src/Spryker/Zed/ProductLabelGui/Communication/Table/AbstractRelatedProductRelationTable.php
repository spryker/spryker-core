<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Table;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

abstract class AbstractRelatedProductRelationTable extends AbstractRelatedProductTable
{

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $this->configureHeader($config);
        $this->configureRawColumns($config);
        $this->configureSorting($config);
        $this->configureSearching($config);
        $this->configureUrl($config);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureHeader(TableConfiguration $config)
    {
        $config->setHeader([
            static::COL_SELECT_CHECKBOX => 'Select',
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT => 'ID',
            SpyProductAbstractTableMap::COL_SKU => 'SKU',
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => 'Name',
            RelatedProductTableQueryBuilder::RESULT_FIELD_ABSTRACT_PRODUCT_CATEGORY_NAMES_CSV => 'Categories',
            RelatedProductTableQueryBuilder::RESULT_FIELD_ABSTRACT_PRODUCT_PRICE => 'Price',
            RelatedProductTableQueryBuilder::RESULT_FIELD_CONCRETE_PRODUCT_STATES_CSV => 'Status',
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureRawColumns(TableConfiguration $config)
    {
        $config->setRawColumns([
            static::COL_SELECT_CHECKBOX,
            RelatedProductTableQueryBuilder::RESULT_FIELD_CONCRETE_PRODUCT_STATES_CSV,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureSorting(TableConfiguration $config)
    {
        $config->setDefaultSortField(
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
            TableConfiguration::SORT_ASC
        );

        $config->setSortable([
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
            SpyProductAbstractTableMap::COL_SKU,
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureSearching(TableConfiguration $config)
    {
        $config->setSearchable([
            SpyProductAbstractTableMap::COL_SKU,
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureUrl(TableConfiguration $config)
    {
        $config->setUrl(sprintf(
            '%s?%s=%s',
            $this->defaultUrl,
            static::PARAM_ID_PRODUCT_LABEL,
            (int)$this->idProductLabel
        ));
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return array
     */
    protected function getRow(SpyProductAbstract $productAbstractEntity)
    {
        $row = parent::getRow($productAbstractEntity);

        $row[static::COL_SELECT_CHECKBOX] = $this->getSelectCheckboxColumn($productAbstractEntity);
        $row[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT] = $productAbstractEntity->getIdProductAbstract();
        $row[SpyProductAbstractTableMap::COL_SKU] = $productAbstractEntity->getSku();

        return $row;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract[]|\Propel\Runtime\Collection\ObjectCollection $productAbstractEntities
     *
     * @return array
     */
    protected function buildResultData(ObjectCollection $productAbstractEntities)
    {
        $tableRows = [];

        foreach ($productAbstractEntities as $productAbstractEntity) {
            $tableRows[] = [
                static::COL_SELECT_CHECKBOX => $this->getSelectCheckboxColumn($productAbstractEntity),
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT => $productAbstractEntity->getIdProductAbstract(),
                SpyProductAbstractTableMap::COL_SKU => $productAbstractEntity->getSku(),
                SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => $this->getNameColumn($productAbstractEntity),
                RelatedProductTableQueryBuilder::RESULT_FIELD_ABSTRACT_PRODUCT_CATEGORY_NAMES_CSV => $this->getCategoriesColumn($productAbstractEntity),
                RelatedProductTableQueryBuilder::RESULT_FIELD_ABSTRACT_PRODUCT_PRICE => $this->getPriceColumn($productAbstractEntity),
                RelatedProductTableQueryBuilder::RESULT_FIELD_CONCRETE_PRODUCT_STATES_CSV => $this->getStatusColumn($productAbstractEntity),
            ];
        }

        return $tableRows;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function getSelectCheckboxColumn(SpyProductAbstract $productAbstractEntity)
    {
        return sprintf(
            '<input class="%s" type="checkbox" name="abstractProduct[]" value="%s" %s data-info="%s"/>',
            'js-abstract-product-checkbox',
            $productAbstractEntity->getIdProductAbstract(),
            $this->getCheckboxCheckedAttribute(),
            htmlspecialchars(json_encode([
                'id' => $productAbstractEntity->getIdProductAbstract(),
                'sku' => $productAbstractEntity->getSku(),
                'name' => $this->getNameColumn($productAbstractEntity),
            ]))
        );
    }

    /**
     * @return string
     */
    abstract protected function getCheckboxCheckedAttribute();

}
