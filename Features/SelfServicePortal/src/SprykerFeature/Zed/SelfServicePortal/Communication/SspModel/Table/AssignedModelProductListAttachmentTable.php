<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Table;

use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\SelfServicePortal\Communication\Reader\RelationCsvReaderInterface;

class AssignedModelProductListAttachmentTable extends AbstractTable
{
    /**
     * @var string
     */
    protected const DEFAULT_URL = 'assigned-product-list-table';

    /**
     * @var string
     */
    protected const TABLE_IDENTIFIER = 'assigned-model-product-list-table';

    /**
     * @var string
     */
    protected const COLUMN_ID = SpyProductListTableMap::COL_ID_PRODUCT_LIST;

    /**
     * @var string
     */
    protected const COLUMN_TITLE = SpyProductListTableMap::COL_TITLE;

    /**
     * @var string
     */
    protected const COLUMN_SELECTED = 'action';

    /**
     * @var string
     */
    protected const REQUEST_PARAM_ID_SSP_MODEL = 'id-ssp-model';

    public function __construct(
        protected SpyProductListQuery $productListQuery,
        protected int $idSspModel
    ) {
        $this->defaultUrl = static::DEFAULT_URL;
        $this->setTableIdentifier(static::TABLE_IDENTIFIER);
    }

    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COLUMN_SELECTED => '',
            static::COLUMN_ID => 'ID',
            static::COLUMN_TITLE => 'Title',
        ]);

        $config->setSearchable([
            static::COLUMN_TITLE,
        ]);

        $config->setSortable([
            static::COLUMN_ID,
            static::COLUMN_TITLE,
        ]);

        $config->addRawColumn(static::COLUMN_SELECTED);
        $config->setUrl(Url::generate('/assigned-product-list-table', [
            static::REQUEST_PARAM_ID_SSP_MODEL => $this->idSspModel,
        ])->build());

        $config->setTableAttributes([
            'data-selectable' => [
                'moveToSelector' => '#productListsToBeUnassigned',
                'inputSelector' => '#attachModel_productListIdsToBeUnassigned',
                'counterHolderSelector' => 'a[href="#tab-content-product-lists-to-be-unassigned"]',
                'colId' => 'spy_product_list.id_product_list',
            ],
            'data-uploader' => [
                'url' => '/self-service-portal/attach-model/get-product-list-relations-from-csv?id-ssp-model=' . $this->idSspModel,
                'path' => RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_UNASSIGNED,
            ],
        ]);

        return $config;
    }

    protected function prepareQuery(): SpyProductListQuery
    {
        /** @var \Orm\Zed\ProductList\Persistence\SpyProductListQuery $query */
        $query = $this->productListQuery
            ->useSpySspModelToProductListQuery()
                ->filterByFkSspModel($this->idSspModel)
            ->endUse()
            ->select([
                SpyProductListTableMap::COL_ID_PRODUCT_LIST,
                SpyProductListTableMap::COL_TITLE,
            ]);

        return $query;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array<int, array<string, mixed>>
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $queryResults = $this->runQuery($this->prepareQuery(), $config);
        $results = [];
        foreach ($queryResults as $row) {
            $results[] = $this->formatRow($row);
        }

        return $results;
    }

    /**
     * @param array<string, mixed> $row
     *
     * @return array<string, mixed>
     */
    protected function formatRow(array $row): array
    {
        $id = (int)$row[SpyProductListTableMap::COL_ID_PRODUCT_LIST];

        return [
            static::COLUMN_SELECTED => sprintf('<input class="js-selectable-table-checkbox" type="checkbox" value="%d" />', $id),
            static::COLUMN_ID => $id,
            static::COLUMN_TITLE => $row[SpyProductListTableMap::COL_TITLE],
        ];
    }

    /**
     * @param array<mixed> $productListIds
     *
     * @return array<string, mixed>
     */
    public function fetchProductListsByIds(array $productListIds): array
    {
        $this->init();

        $this->productListQuery->filterByIdProductList_In($productListIds);

        /**
         * @var array<string, mixed> $data
         */
        $data = $this->prepareData($this->config);

        $this->loadData($data);

        $dataWithoutCheckboxColumn = array_map(function ($productListRow) {
             unset($productListRow[static::COLUMN_SELECTED]);

             return array_values($productListRow);
        }, $data);

        return $dataWithoutCheckboxColumn;
    }
}
