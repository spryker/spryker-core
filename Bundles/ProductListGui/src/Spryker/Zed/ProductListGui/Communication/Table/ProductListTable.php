<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Table;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\QueryJoinTransfer;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductListGui\Communication\Controller\ProductListAbstractController;
use Spryker\Zed\ProductListGui\Communication\Controller\RoutingConstants;
use Spryker\Zed\ProductListGui\Communication\Table\PluginExecutor\ProductListTablePluginExecutorInterface;

class ProductListTable extends AbstractTable
{
    protected const COLUMN_ID_PRODUCT_LIST = SpyProductListTableMap::COL_ID_PRODUCT_LIST;
    protected const COLUMN_TITLE = SpyProductListTableMap::COL_TITLE;
    protected const COLUMN_TYPE = SpyProductListTableMap::COL_TYPE;
    protected const COLUMN_ACTIONS = 'actions';

    public const URL_PRODUCT_LIST_EDIT = RoutingConstants::URL_EDIT;
    public const URL_PRODUCT_LIST_DELETE = RoutingConstants::URL_DELETE;

    /**
     * @var \Orm\Zed\ProductList\Persistence\SpyProductListQuery
     */
    protected $productListQuery;

    /**
     * @var \Spryker\Zed\ProductListGui\Communication\Table\PluginExecutor\ProductListTablePluginExecutorInterface
     */
    protected $productListTablePluginExecutor;

    /**
     * @param \Orm\Zed\ProductList\Persistence\SpyProductListQuery $productListQuery
     * @param \Spryker\Zed\ProductListGui\Communication\Table\PluginExecutor\ProductListTablePluginExecutorInterface $productListTablePluginExecutor
     */
    public function __construct(
        SpyProductListQuery $productListQuery,
        ProductListTablePluginExecutorInterface $productListTablePluginExecutor
    ) {
        $this->productListQuery = $productListQuery;
        $this->productListTablePluginExecutor = $productListTablePluginExecutor;
    }

    /**
     * @module ProductList
     *
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $this->expandQuery();

        $queryResults = $this->runQuery($this->productListQuery, $config);

        $results = [];

        foreach ($queryResults as $item) {
            $rowData = [
                static::COLUMN_ID_PRODUCT_LIST => $item[SpyProductListTableMap::COL_ID_PRODUCT_LIST],
                static::COLUMN_TITLE => $item[SpyProductListTableMap::COL_TITLE],
                static::COLUMN_TYPE => $this->generateTypeLabels($item),
                static::COLUMN_ACTIONS => $this->buildLinks($item),
            ];

            $rowData += $this->productListTablePluginExecutor->executeTableDataExpanderPlugins($item);
            $results[] = $rowData;
        }

        unset($queryResults);

        return $results;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config = $this->setHeader($config);
        $config->addRawColumn(static::COLUMN_TYPE);
        $config->addRawColumn(static::COLUMN_ACTIONS);

        $config->setSortable(
            [
                static::COLUMN_ID_PRODUCT_LIST,
                static::COLUMN_TITLE,
                static::COLUMN_TYPE,
            ]
        );

        $config->setSearchable(
            [
                static::COLUMN_ID_PRODUCT_LIST,
                static::COLUMN_TITLE,
            ]
        );

        $config->setDefaultSortField(static::COLUMN_ID_PRODUCT_LIST, TableConfiguration::SORT_DESC);

        return $this->productListTablePluginExecutor->executeTableConfigExpanderPlugins($config);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setHeader(TableConfiguration $config): TableConfiguration
    {
        $baseData = [
            static::COLUMN_ID_PRODUCT_LIST => 'ID',
            static::COLUMN_TITLE => 'Title',
            static::COLUMN_TYPE => 'Type',
        ];

        $externalData = $this->productListTablePluginExecutor->executeTableHeaderExpanderPlugins();

        $actions = [static::COLUMN_ACTIONS => 'Actions'];

        $config->setHeader($baseData + $externalData + $actions);

        return $config;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function generateTypeLabels(array $item): string
    {
        if ($item[SpyProductListTableMap::COL_TYPE] === SpyProductListTableMap::COL_TYPE_WHITELIST) {
            return $this->generateLabel('Whitelist', 'label-info');
        }

        return $this->generateLabel('Blacklist', 'label-warning');
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function buildLinks(array $item): string
    {
        $buttons = [];

        $editUrl = Url::generate(static::URL_PRODUCT_LIST_EDIT, [ProductListAbstractController::URL_PARAM_ID_PRODUCT_LIST => $item[SpyProductListTableMap::COL_ID_PRODUCT_LIST]]);
        $deleteUrl = Url::generate(static::URL_PRODUCT_LIST_DELETE, [ProductListAbstractController::URL_PARAM_ID_PRODUCT_LIST => $item[SpyProductListTableMap::COL_ID_PRODUCT_LIST]]);

        $buttons[] = $this->generateEditButton($editUrl, 'Edit List');
        $buttons[] = $this->generateRemoveButton($deleteUrl, 'Remove List');

        $expandedButtons = $this->productListTablePluginExecutor->executeTableActionExpanderPlugins($item);
        foreach ($expandedButtons as $button) {
            if (!$button->getUrl()) {
                continue;
            }
            $buttons[] = $this->generateButton(
                $button->getUrl(),
                $button->getTitle(),
                $button->getDefaultOptions(),
                $button->getCustomOptions()
            );
        }

        return implode(' ', $buttons);
    }

    /**
     * Expands query with params from expander plugins.
     *
     * @return void
     */
    protected function expandQuery(): void
    {
        $queryCriteriaTransfer = $this->productListTablePluginExecutor
            ->executeTableQueryCriteriaExpanderPlugins(new QueryCriteriaTransfer());

        foreach ($queryCriteriaTransfer->getWithColumns() as $field => $value) {
            $this->productListQuery->withColumn($field, $value);
        }

        /**
         * @var \ArrayObject|\Generated\Shared\Transfer\QueryJoinTransfer[]|null
         */
        $queryCriteriaTransferJoins = $queryCriteriaTransfer->getJoins();

        if (!$queryCriteriaTransferJoins) {
            return;
        }

        foreach ($queryCriteriaTransferJoins as $queryJoinTransfer) {
            if ($queryJoinTransfer->getRelation()) {
                $this->expandQueryWithRelation($queryJoinTransfer);

                continue;
            }

            $this->expandQueryWithoutRelation($queryJoinTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QueryJoinTransfer $queryJoinTransfer
     *
     * @return void
     */
    protected function expandQueryWithRelation(QueryJoinTransfer $queryJoinTransfer): void
    {
        $this->productListQuery->join($queryJoinTransfer->getRelation(), $queryJoinTransfer->getJoinType());

        if ($queryJoinTransfer->getCondition()) {
            $this->productListQuery->addJoinCondition(
                $queryJoinTransfer->getRelation(),
                $queryJoinTransfer->getCondition()
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QueryJoinTransfer $queryJoinTransfer
     *
     * @return void
     */
    protected function expandQueryWithoutRelation(QueryJoinTransfer $queryJoinTransfer): void
    {
        $this->productListQuery->addJoin(
            $queryJoinTransfer->getLeft(),
            $queryJoinTransfer->getRight(),
            $queryJoinTransfer->getJoinType()
        );
    }
}
