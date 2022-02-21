<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Communication\Table;

use Orm\Zed\Product\Persistence\Map\SpyProductAttributeKeyTableMap;
use Orm\Zed\ProductSearch\Persistence\Map\SpyProductSearchAttributeTableMap;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductSearch\Communication\Controller\SearchPreferencesController;
use Spryker\Zed\ProductSearch\Communication\Form\DeleteFilterPreferencesForm;
use Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface;

class FilterPreferencesTable extends AbstractTable
{
    /**
     * @var string
     */
    public const COL_NAME = 'attribute_key';
    public const COL_POSITION = SpyProductSearchAttributeTableMap::COL_POSITION;
    public const COL_FILTER_TYPE = SpyProductSearchAttributeTableMap::COL_FILTER_TYPE;

    /**
     * @var string
     */
    public const ACTIONS = 'actions';

    /**
     * @var \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface
     */
    protected $productSearchQueryContainer;

    /**
     * @param \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface $productSearchQueryContainer
     */
    public function __construct(ProductSearchQueryContainerInterface $productSearchQueryContainer)
    {
        $this->productSearchQueryContainer = $productSearchQueryContainer;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader($this->getHeaderFields());
        $config->setSearchable($this->getSearchableFields());
        $config->setSortable($this->getSortableFields());

        $config->addRawColumn(static::ACTIONS);

        return $config;
    }

    /**
     * @return array
     */
    protected function getHeaderFields()
    {
        return [
            static::COL_POSITION => 'Filter position',
            static::COL_NAME => 'Attribute key',
            static::COL_FILTER_TYPE => 'Filter type',
            static::ACTIONS => 'Actions',
        ];
    }

    /**
     * @return array
     */
    protected function getSearchableFields()
    {
        return [
            static::COL_NAME => SpyProductAttributeKeyTableMap::COL_KEY,
            static::COL_FILTER_TYPE,
        ];
    }

    /**
     * @return array
     */
    protected function getSortableFields()
    {
        return [
            static::COL_POSITION,
            static::COL_NAME,
            static::COL_FILTER_TYPE,
        ];
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $result = [];

        $productSearchAttributes = $this->getProductSearchAttributes($config);

        foreach ($productSearchAttributes as $productSearchAttributeEntity) {
            $result[] = [
                static::COL_POSITION => $productSearchAttributeEntity->getPosition(),
                static::COL_NAME => $productSearchAttributeEntity->getSpyProductAttributeKey()->getKey(),
                static::COL_FILTER_TYPE => $productSearchAttributeEntity->getFilterType(),
                static::ACTIONS => $this->getActions($productSearchAttributeEntity->getIdProductSearchAttribute()),
            ];
        }

        return $result;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array<\Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttribute>
     */
    protected function getProductSearchAttributes(TableConfiguration $config)
    {
        $query = $this
            ->productSearchQueryContainer
            ->queryFilterPreferencesTable()
            ->withColumn(SpyProductAttributeKeyTableMap::COL_KEY, static::COL_NAME);

        $productAttributeKey = $this->runQuery($query, $config, true);

        /** @phpstan-var array<\Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttribute> */
        return $productAttributeKey;
    }

    /**
     * @param int $idProductSearchAttribute
     *
     * @return string
     */
    protected function getActions($idProductSearchAttribute)
    {
        $actions = [
            $this->generateEditButton(
                sprintf(
                    '/product-search/filter-preferences/edit?%s=%d',
                    SearchPreferencesController::PARAM_ID,
                    $idProductSearchAttribute,
                ),
                'Edit',
            ),
            $this->generateViewButton(
                sprintf(
                    '/product-search/filter-preferences/view?%s=%d',
                    SearchPreferencesController::PARAM_ID,
                    $idProductSearchAttribute,
                ),
                'View',
            ),
            $this->generateRemoveButton(
                sprintf(
                    '/product-search/filter-preferences/delete?%s=%d',
                    SearchPreferencesController::PARAM_ID,
                    $idProductSearchAttribute,
                ),
                'Delete',
                [],
                DeleteFilterPreferencesForm::class,
            ),
        ];

        return implode(' ', $actions);
    }
}
