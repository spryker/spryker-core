<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Table;

use Orm\Zed\SelfServicePortal\Persistence\Map\SpySspAssetTableMap;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\SelfServicePortal\Communication\Reader\RelationCsvReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class AssignedModelAssetAttachmentTable extends AbstractTable
{
    /**
     * @var string
     */
    protected const DEFAULT_URL = 'assigned-ssp-asset-table';

    /**
     * @var string
     */
    protected const TABLE_IDENTIFIER = 'assigned-model-asset-table';

    /**
     * @var string
     */
    protected const COLUMN_ID = SpySspAssetTableMap::COL_ID_SSP_ASSET;

    /**
     * @var string
     */
    protected const COLUMN_REFERENCE = SpySspAssetTableMap::COL_REFERENCE;

    /**
     * @var string
     */
    protected const COLUMN_NAME = SpySspAssetTableMap::COL_NAME;

    /**
     * @var string
     */
    protected const COLUMN_SERIAL = SpySspAssetTableMap::COL_SERIAL_NUMBER;

    /**
     * @var string
     */
    protected const COLUMN_STATUS = SpySspAssetTableMap::COL_STATUS;

    /**
     * @var string
     */
    protected const COLUMN_SELECTED = 'action';

    /**
     * @var string
     */
    protected const REQUEST_PARAM_ID_SSP_MODEL = 'id-ssp-model';

    public function __construct(
        protected SpySspAssetQuery $sspAssetQuery,
        protected int $idSspModel,
        protected SelfServicePortalConfig $selfServicePortalConfig
    ) {
        $this->defaultUrl = static::DEFAULT_URL;
        $this->setTableIdentifier(static::TABLE_IDENTIFIER);
    }

    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COLUMN_SELECTED => '',
            static::COLUMN_ID => 'ID',
            static::COLUMN_NAME => 'Name',
            static::COLUMN_REFERENCE => 'Asset Reference',
            static::COLUMN_SERIAL => 'Serial Number',
            static::COLUMN_STATUS => 'Status',
        ]);

        $config->setSearchable([
            static::COLUMN_NAME,
            static::COLUMN_REFERENCE,
            static::COLUMN_SERIAL,
            static::COLUMN_STATUS,
        ]);

        $config->setSortable([
            static::COLUMN_ID,
            static::COLUMN_NAME,
            static::COLUMN_REFERENCE,
            static::COLUMN_SERIAL,
            static::COLUMN_STATUS,
        ]);

        $config->addRawColumn(static::COLUMN_SELECTED);
        $config->addRawColumn(static::COLUMN_STATUS);
        $config->setUrl(Url::generate('/assigned-ssp-asset-table', [
            static::REQUEST_PARAM_ID_SSP_MODEL => $this->idSspModel,
        ])->build());

        $config->setTableAttributes([
            'data-selectable' => [
                'moveToSelector' => '#assetsToBeUnassigned',
                'inputSelector' => '#attachModel_sspAssetIdsToBeUnassigned',
                'counterHolderSelector' => 'a[href="#tab-content-assets-to-be-unassigned"]',
                'colId' => static::COLUMN_ID,
            ],
            'data-uploader' => [
                'url' => '/self-service-portal/attach-model/get-relations-from-csv?id-ssp-model=' . $this->idSspModel,
                'path' => RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_UNASSIGNED,
            ],
        ]);

        return $config;
    }

    protected function prepareQuery(): SpySspAssetQuery
    {
        /** @var \Orm\Zed\SelfServicePortal\Persistence\SpySspAssetQuery $query */
        $query = $this->sspAssetQuery
            ->useSpySspAssetToSspModelQuery()
                ->filterByFkSspModel($this->idSspModel)
            ->endUse()
            ->select([
                SpySspAssetTableMap::COL_ID_SSP_ASSET,
                SpySspAssetTableMap::COL_REFERENCE,
                SpySspAssetTableMap::COL_NAME,
                SpySspAssetTableMap::COL_SERIAL_NUMBER,
                SpySspAssetTableMap::COL_STATUS,
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
        $id = (int)$row[SpySspAssetTableMap::COL_ID_SSP_ASSET];

        return [
            static::COLUMN_SELECTED => sprintf('<input class="js-selectable-table-checkbox" type="checkbox" value="%d" />', $id),
            static::COLUMN_ID => $id,
            static::COLUMN_NAME => $row[SpySspAssetTableMap::COL_NAME],
            static::COLUMN_REFERENCE => $row[SpySspAssetTableMap::COL_REFERENCE],
            static::COLUMN_SERIAL => $row[SpySspAssetTableMap::COL_SERIAL_NUMBER],
            static::COLUMN_STATUS => $this->generateLabel(
                $row[SpySspAssetTableMap::COL_STATUS] ?? '',
                $this->selfServicePortalConfig->getAssetStatusClassMap()[$row[SpySspAssetTableMap::COL_STATUS]] ?? null,
            ),
        ];
    }

    /**
     * @param array<string> $sspAssetReferences
     *
     * @return array<string, mixed>
     */
    public function fetchAssetsByReferences(array $sspAssetReferences): array
    {
        $this->init();

        $this->sspAssetQuery->filterByReference_In($sspAssetReferences);

        /**
         * @var array<string, mixed> $data
         */
        $data = $this->prepareData($this->config);

        $this->loadData($data);

        $dataWithoutCheckboxColumn = array_map(function ($assetRow) {
            unset($assetRow[static::COLUMN_SELECTED]);

            return array_values($assetRow);
        }, $data);

        return $dataWithoutCheckboxColumn;
    }
}
