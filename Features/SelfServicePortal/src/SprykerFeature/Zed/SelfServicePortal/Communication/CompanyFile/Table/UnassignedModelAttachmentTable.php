<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Table;

use Orm\Zed\SelfServicePortal\Persistence\Map\SpySspModelTableMap;
use Orm\Zed\SelfServicePortal\Persistence\Map\SpySspModelToFileTableMap;
use Orm\Zed\SelfServicePortal\Persistence\SpySspModelQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;
use SprykerFeature\Zed\SelfServicePortal\Communication\Controller\FileAbstractController;
use SprykerFeature\Zed\SelfServicePortal\Communication\Reader\RelationCsvReaderInterface;

class UnassignedModelAttachmentTable extends AbstractTable
{
    /**
     * @var string
     */
    protected const DEFAULT_URL = 'available-model-table';

    /**
     * @var string
     */
    protected const TABLE_IDENTIFIER = 'available-model-attachment-table';

    /**
     * @var string
     */
    protected const COLUMN_ID = SpySspModelTableMap::COL_ID_SSP_MODEL;

    /**
     * @var string
     */
    protected const COLUMN_REFERENCE = SpySspModelTableMap::COL_REFERENCE;

    /**
     * @var string
     */
    protected const COLUMN_NAME = SpySspModelTableMap::COL_NAME;

    /**
     * @var string
     */
    protected const COLUMN_CODE = SpySspModelTableMap::COL_CODE;

    /**
     * @var string
     */
    protected const COLUMN_SELECTED = 'action';

    /**
     * @var int
     */
    protected int $idFile;

    /**
     * @var \Orm\Zed\SelfServicePortal\Persistence\SpySspModelQuery
     */
    protected SpySspModelQuery $sspModelQuery;

    public function __construct(SpySspModelQuery $sspModelQuery, int $idFile)
    {
        $this->sspModelQuery = $sspModelQuery;
        $this->idFile = $idFile;
        $this->defaultUrl = static::DEFAULT_URL;
        $this->setTableIdentifier(static::TABLE_IDENTIFIER);
    }

    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COLUMN_SELECTED => '',
            static::COLUMN_ID => 'ID',
            static::COLUMN_REFERENCE => 'Model Reference',
            static::COLUMN_NAME => 'Model Name',
            static::COLUMN_CODE => 'Model Code',
        ]);

        $config->setSearchable([
            SpySspModelTableMap::COL_NAME,
            SpySspModelTableMap::COL_REFERENCE,
            SpySspModelTableMap::COL_CODE,
        ]);

        $config->setSortable([
            static::COLUMN_REFERENCE,
            static::COLUMN_NAME,
            static::COLUMN_CODE,
        ]);

        $config->addRawColumn(static::COLUMN_SELECTED);

        $config->setDefaultSortField(static::COLUMN_REFERENCE, TableConfiguration::SORT_ASC);

        $config->setTableAttributes([
            'data-selectable' => [
                'moveToSelector' => '#modelsToBeAssigned',
                'inputSelector' => '#fileAttachment_sspModelIdsToBeAssigned',
                'counterHolderSelector' => 'a[href="#tab-content-models-to-be-assigned"]',
                'colId' => static::COLUMN_ID,
            ],
            'data-uploader' => [
                'url' => sprintf('/self-service-portal/attach-file/get-model-attachments-from-csv?id-file=%d', $this->idFile),
                'path' => RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_ASSIGNED,
            ],
        ]);

        $config->setUrl(Url::generate('/available-model-table', [
            FileAbstractController::REQUEST_PARAM_ID_FILE => $this->idFile,
        ])->build());

        return $config;
    }

    protected function prepareQuery(): SpySspModelQuery
    {
        /** @var \Orm\Zed\SelfServicePortal\Persistence\SpySspModelQuery $query */
        $query = $this->sspModelQuery
            ->useSpySspModelToFileQuery(SpySspModelToFileTableMap::TABLE_NAME, Criteria::LEFT_JOIN)
                ->filterByFkFile(null, Criteria::ISNULL)
                ->_or()
                ->filterByFkFile($this->idFile, Criteria::NOT_EQUAL)
            ->endUse()
            ->select([
                SpySspModelTableMap::COL_ID_SSP_MODEL,
                SpySspModelTableMap::COL_REFERENCE,
                SpySspModelTableMap::COL_NAME,
                SpySspModelTableMap::COL_CODE,
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
        $id = (int)$row[SpySspModelTableMap::COL_ID_SSP_MODEL];

        return [
            static::COLUMN_SELECTED => sprintf('<input class="js-selectable-table-checkbox" type="checkbox" value="%d" />', $id),
            static::COLUMN_ID => $id,
            static::COLUMN_REFERENCE => (string)$row[SpySspModelTableMap::COL_REFERENCE],
            static::COLUMN_NAME => (string)$row[SpySspModelTableMap::COL_NAME],
            static::COLUMN_CODE => (string)($row[SpySspModelTableMap::COL_CODE] ?: '---'),
        ];
    }

    /**
     * @param array<string> $modelReferences
     *
     * @return array<array<mixed>>
     */
    public function fetchModelsByReferences(array $modelReferences): array
    {
        $this->init();

        $this->sspModelQuery->filterByReference_In($modelReferences);

        /**
         * @var array<string, mixed> $data
         */
        $data = $this->prepareData($this->config);

        $this->loadData($data);

        $dataWithoutCheckboxColumn = array_map(function ($modelRow) {
             unset($modelRow[static::COLUMN_SELECTED]);

             return array_values($modelRow);
        }, $data);

        return $dataWithoutCheckboxColumn;
    }
}
