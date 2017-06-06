<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Table;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductManagement\Communication\Controller\ViewController;

class RelatedProductOverviewTable extends AbstractRelatedProductTable
{

    const PARAM_ID_PRODUCT_LABEL = 'id-product-label';
    const TABLE_IDENTIFIER = 'related-products-table';
    const COL_ACTIONS = 'actions';

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $this->setTableIdentifier(static::TABLE_IDENTIFIER);

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
            SpyProductAbstractTableMap::COL_SKU => 'SKU',
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => 'Name',
            RelatedProductTableQueryBuilder::RESULT_FIELD_ABSTRACT_PRODUCT_CATEGORY_NAMES_CSV => 'Categories',
            RelatedProductTableQueryBuilder::RESULT_FIELD_ABSTRACT_PRODUCT_PRICE => 'Price',
            RelatedProductTableQueryBuilder::RESULT_FIELD_ABSTRACT_PRODUCT_RELATION_COUNT => '# of Other Labels',
            RelatedProductTableQueryBuilder::RESULT_FIELD_CONCRETE_PRODUCT_STATES_CSV => 'Status',
            static::COL_ACTIONS => 'Actions',
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
            RelatedProductTableQueryBuilder::RESULT_FIELD_CONCRETE_PRODUCT_STATES_CSV,
            static::COL_ACTIONS,
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
            SpyProductAbstractTableMap::COL_SKU,
            TableConfiguration::SORT_ASC
        );

        $config->setSortable([
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
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function getQuery()
    {
        return $this->tableQueryBuilder->buildAssignedProductQuery($this->idProductLabel);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return array
     */
    protected function getRow(SpyProductAbstract $productAbstractEntity)
    {
        $row = parent::getRow($productAbstractEntity);

        $row[SpyProductAbstractTableMap::COL_SKU] = $productAbstractEntity->getSku();
        $row[RelatedProductTableQueryBuilder::RESULT_FIELD_ABSTRACT_PRODUCT_RELATION_COUNT] = $this->getAdditionalRelationCountColumn($productAbstractEntity);
        $row[static::COL_ACTIONS] = $this->getActionsColumn($productAbstractEntity);

        return $row;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function getAdditionalRelationCountColumn(SpyProductAbstract $productAbstractEntity)
    {
        $relationCount = (int)$productAbstractEntity->getVirtualColumn(
            RelatedProductTableQueryBuilder::RESULT_FIELD_ABSTRACT_PRODUCT_RELATION_COUNT
        );

        return ($relationCount - 1);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function getActionsColumn(SpyProductAbstract $productAbstractEntity)
    {
        $actionButtons = [
            $this->generateViewProductInZedButton($productAbstractEntity),
        ];

        return implode(' ', $actionButtons);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function generateViewProductInZedButton(SpyProductAbstract $productAbstractEntity)
    {
        return $this->generateViewButton(
            Url::generate(
                '/product-management/view',
                [
                    ViewController::PARAM_ID_PRODUCT_ABSTRACT => $productAbstractEntity->getIdProductAbstract(),
                ]
            ),
            'View'
        );
    }

}
