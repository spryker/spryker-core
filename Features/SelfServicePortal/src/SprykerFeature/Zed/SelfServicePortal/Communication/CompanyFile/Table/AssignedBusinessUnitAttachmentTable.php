<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Table;

use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\SelfServicePortal\Communication\Controller\FileAbstractController;

class AssignedBusinessUnitAttachmentTable extends AbstractTable
{
    /**
     * @var string
     */
    protected const TABLE_IDENTIFIER = 'assigned-business-unit-table';

    protected const COLUMN_ID_BUSINESS_UNIT = SpyCompanyBusinessUnitTableMap::COL_ID_COMPANY_BUSINESS_UNIT;

    protected const COLUMN_COMPANY_NAME = SpyCompanyTableMap::COL_NAME;

    protected const COLUMN_BUSINESS_UNIT_NAME = SpyCompanyBusinessUnitTableMap::COL_NAME;

    /**
     * @var string
     */
    protected const COLUMN_SELECTED = 'action';

    public function __construct(
        protected SpyCompanyBusinessUnitQuery $companyBusinessUnitQuery,
        protected int $idFile
    ) {
        $this->setTableIdentifier(static::TABLE_IDENTIFIER);
    }

    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COLUMN_SELECTED => '',
            static::COLUMN_ID_BUSINESS_UNIT => 'ID',
            static::COLUMN_COMPANY_NAME => 'Company Name',
            static::COLUMN_BUSINESS_UNIT_NAME => 'Business Unit Name',
        ]);

        $config->setSearchable([
            static::COLUMN_ID_BUSINESS_UNIT,
            static::COLUMN_COMPANY_NAME,
            static::COLUMN_BUSINESS_UNIT_NAME,
        ]);

        $config->setSortable([
            static::COLUMN_ID_BUSINESS_UNIT,
            static::COLUMN_COMPANY_NAME,
            static::COLUMN_BUSINESS_UNIT_NAME,
        ]);

        $config->addRawColumn(static::COLUMN_SELECTED);
        $config->setTableAttributes([
            'data-selectable' => [
                'moveToSelector' => '#businessUnitsToBeDeassigned',
                'inputSelector' => '#fileAttachment_businessUnitIdsToBeDeassigned',
                'counterHolderSelector' => 'a[href="#tab-content-business-units-to-be-detached"]',
                'colId' => static::COLUMN_ID_BUSINESS_UNIT,
            ],
        ]);
        $config->setUrl(Url::generate('/assigned-business-unit-table', [
            FileAbstractController::REQUEST_PARAM_ID_FILE => $this->idFile,
        ])->build());

        return $config;
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

    protected function prepareQuery(): SpyCompanyBusinessUnitQuery
    {
        /** @var \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery $query */
        $query = $this->companyBusinessUnitQuery
            ->useSpyCompanyBusinessUnitFileQuery()
                ->filterByFkFile($this->idFile)
            ->endUse();

        return $query
            ->leftJoinWithCompany()
            ->select([
                SpyCompanyBusinessUnitTableMap::COL_ID_COMPANY_BUSINESS_UNIT,
                SpyCompanyTableMap::COL_NAME,
                SpyCompanyBusinessUnitTableMap::COL_NAME,
            ]);
    }

    /**
     * @param array<string, mixed> $row
     *
     * @return array<int|string, int|string>
     */
    protected function formatRow(array $row): array
    {
        $id = (int)$row[SpyCompanyBusinessUnitTableMap::COL_ID_COMPANY_BUSINESS_UNIT];

        return [
            static::COLUMN_SELECTED => sprintf('<input class="js-selectable-table-checkbox" type="checkbox" value="%d" />', $id),
            static::COLUMN_ID_BUSINESS_UNIT => $id,
            static::COLUMN_COMPANY_NAME => (string)$row[SpyCompanyTableMap::COL_NAME],
            static::COLUMN_BUSINESS_UNIT_NAME => (string)$row[SpyCompanyBusinessUnitTableMap::COL_NAME],
        ];
    }
}
