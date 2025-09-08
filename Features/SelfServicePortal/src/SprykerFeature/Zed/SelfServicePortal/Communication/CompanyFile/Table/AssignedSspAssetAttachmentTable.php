<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Table;

use Orm\Zed\SelfServicePortal\Persistence\Map\SpySspAssetTableMap;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\SelfServicePortal\Communication\Controller\FileAbstractController;

class AssignedSspAssetAttachmentTable extends AbstractTable
{
    /**
     * @var string
     */
    protected const DEFAULT_URL = 'assigned-ssp-asset-table';

    /**
     * @var string
     */
    protected const TABLE_IDENTIFIER = 'assigned-ssp-asset-table';

    protected const COLUMN_ID = SpySspAssetTableMap::COL_ID_SSP_ASSET;

    protected const COLUMN_REFERENCE = SpySspAssetTableMap::COL_REFERENCE;

    protected const COLUMN_NAME = SpySspAssetTableMap::COL_NAME;

    protected const COLUMN_SERIAL = SpySspAssetTableMap::COL_SERIAL_NUMBER;

    protected const COLUMN_STATUS = SpySspAssetTableMap::COL_STATUS;

    /**
     * @var string
     */
    protected const COLUMN_SELECTED = 'action';

    public function __construct(protected SpySspAssetQuery $sspAssetQuery, protected int $idFile)
    {
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
            static::COLUMN_NAME,
            static::COLUMN_REFERENCE,
            static::COLUMN_SERIAL,
        ]);

        $config->addRawColumn(static::COLUMN_SELECTED);
        $config->setUrl(Url::generate('/assigned-ssp-asset-table', [
            FileAbstractController::REQUEST_PARAM_ID_FILE => $this->idFile,
        ])->build());

        $config->setTableAttributes([
            'data-selectable' => [
                'moveToSelector' => '#assetsToBeDeassigned',
                'inputSelector' => '#fileAttachment_sspAssetIdsToBeDeassigned',
                'counterHolderSelector' => 'a[href="#tab-content-assets-to-be-detached"]',
                'colId' => static::COLUMN_ID,
            ],
        ]);

        return $config;
    }

    protected function prepareQuery(): SpySspAssetQuery
    {
        /** @var \Orm\Zed\SelfServicePortal\Persistence\SpySspAssetQuery $query */
        $query = $this->sspAssetQuery
            ->useSpySspAssetFileQuery()
                ->filterByFkFile($this->idFile)
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
     * @return array<int, array<int|string, int|string>>
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
     * @return array<int|string, int|string>
     */
    protected function formatRow(array $row): array
    {
        $id = (int)$row[SpySspAssetTableMap::COL_ID_SSP_ASSET];

        return [
            static::COLUMN_SELECTED => sprintf('<input class="js-selectable-table-checkbox" type="checkbox" value="%d" />', $id),
            static::COLUMN_ID => $id,
            static::COLUMN_NAME => (string)$row[SpySspAssetTableMap::COL_NAME],
            static::COLUMN_REFERENCE => (string)$row[SpySspAssetTableMap::COL_REFERENCE],
            static::COLUMN_SERIAL => (string)$row[SpySspAssetTableMap::COL_SERIAL_NUMBER],
            static::COLUMN_STATUS => (string)$row[SpySspAssetTableMap::COL_STATUS],
        ];
    }
}
