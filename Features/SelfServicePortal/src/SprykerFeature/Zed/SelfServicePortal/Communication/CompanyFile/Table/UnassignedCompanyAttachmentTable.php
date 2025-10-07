<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Table;

use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\SelfServicePortal\Persistence\Map\SpyCompanyBusinessUnitFileTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\SelfServicePortal\Communication\Controller\FileAbstractController;
use SprykerFeature\Zed\SelfServicePortal\Communication\Reader\RelationCsvReaderInterface;

class UnassignedCompanyAttachmentTable extends AbstractTable
{
    /**
     * @var string
     */
    protected const TABLE_IDENTIFIER = 'available-company-table';

    /**
     * @var string
     */
    protected const COLUMN_SELECTED = 'action';

    /**
     * @var string
     */
    protected const COLUMN_ID = 'id_company';

    /**
     * @var string
     */
    protected const COLUMN_COMPANY_NAME = 'company_name';

    public function __construct(
        protected int $idFile,
        protected SpyCompanyQuery $companyQuery
    ) {
        $this->setTableIdentifier(static::TABLE_IDENTIFIER);
    }

    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COLUMN_SELECTED => '',
            static::COLUMN_ID => 'ID',
            static::COLUMN_COMPANY_NAME => 'Company Name',
        ]);

        $config->setSearchable([
            SpyCompanyTableMap::COL_ID_COMPANY,
            SpyCompanyTableMap::COL_NAME,
        ]);

        $config->setSortable([
            SpyCompanyTableMap::COL_ID_COMPANY,
            SpyCompanyTableMap::COL_NAME,
        ]);
        $config->addRawColumn(static::COLUMN_SELECTED);

        $config->setTableAttributes([
            'data-selectable' => [
                'moveToSelector' => '#companiesToBeAssigned',
                'inputSelector' => '#fileAttachment_companyIdsToBeAssigned',
                'counterHolderSelector' => 'a[href="#tab-content-companies-to-be-attached"]',
                'colId' => static::COLUMN_ID,
            ],
            'data-uploader' => [
                'url' => sprintf('/self-service-portal/attach-file/get-company-attachments-from-csv?id-file=%d', $this->idFile),
                'path' => RelationCsvReaderInterface::KEY_ENTITY_IDENTIFIERS_TO_BE_ASSIGNED,
            ],
        ]);
        $config->setFooter([]);
        $config->setUrl(Url::generate('/available-company-table', [
            FileAbstractController::REQUEST_PARAM_ID_FILE => $this->idFile,
        ])->build());

        return $config;
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

    protected function prepareQuery(): SpyCompanyQuery
    {
        return $this->companyQuery
            ->join('CompanyBusinessUnit')
            ->leftJoin('CompanyBusinessUnit.SpyCompanyBusinessUnitFile')
            ->where(SpyCompanyBusinessUnitFileTableMap::COL_FK_FILE . ' IS NULL OR ' . SpyCompanyBusinessUnitFileTableMap::COL_FK_FILE . ' = ?', $this->idFile)
            ->groupByIdCompany()
            ->having('COUNT(DISTINCT ' . SpyCompanyBusinessUnitTableMap::COL_ID_COMPANY_BUSINESS_UNIT . ') > COUNT(DISTINCT ' . SpyCompanyBusinessUnitFileTableMap::COL_FK_COMPANY_BUSINESS_UNIT . ') OR COUNT(DISTINCT ' . SpyCompanyBusinessUnitFileTableMap::COL_FK_COMPANY_BUSINESS_UNIT . ') = 0')
            ->select([
                SpyCompanyTableMap::COL_ID_COMPANY,
                SpyCompanyTableMap::COL_NAME,
            ]);
    }

    /**
     * @param array<string, mixed> $row
     *
     * @return array<string, mixed>
     */
    protected function formatRow(array $row): array
    {
        $id = (int)$row[SpyCompanyTableMap::COL_ID_COMPANY];

        return [
            static::COLUMN_SELECTED => sprintf('<input class="js-selectable-table-checkbox" type="checkbox" value="%d" data-company-name="%s" />', $id, htmlspecialchars($row[SpyCompanyTableMap::COL_NAME] ?? '')),
            static::COLUMN_ID => $id,
            static::COLUMN_COMPANY_NAME => htmlspecialchars($row[SpyCompanyTableMap::COL_NAME] ?? ''),
        ];
    }

    /**
     * @param array<mixed> $companyIds
     *
     * @return array<mixed>
     */
    public function fetchCompaniesByIds(array $companyIds): array
    {
        $this->init();

        $this->companyQuery->filterByIdCompany_In($companyIds);

        /**
         * @var array<string, mixed> $data
         */
        $data = $this->prepareData($this->config);

        $this->loadData($data);

        $dataWithoutCheckboxColumn = array_map(function ($companyRow) {
             unset($companyRow[static::COLUMN_SELECTED]);

             return array_values($companyRow);
        }, $data);

        return $dataWithoutCheckboxColumn;
    }
}
