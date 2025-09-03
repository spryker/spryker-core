<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Table;

use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\SelfServicePortal\Communication\Controller\FileAbstractController;

class AvailableCompanyUserAttachmentTable extends AbstractTable
{
    /**
     * @var string
     */
    protected const TABLE_IDENTIFIER = 'available-company-user-table';

    /**
     * @var string
     */
    protected const COLUMN_SELECTED = 'action';

    /**
     * @var string
     */
    protected const COLUMN_ID = 'id_company_user';

    /**
     * @var string
     */
    protected const COLUMN_COMPANY_NAME = 'company_name';

    /**
     * @var string
     */
    protected const COLUMN_BUSINESS_UNIT_NAME = 'business_unit_name';

    /**
     * @var string
     */
    protected const COLUMN_COMPANY_USER_NAME = 'company_user_name';

    public function __construct(
        protected int $idFile,
        protected SpyCompanyUserQuery $companyUserQuery
    ) {
        $this->setTableIdentifier(static::TABLE_IDENTIFIER);
    }

    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COLUMN_SELECTED => '',
            static::COLUMN_ID => 'ID',
            static::COLUMN_COMPANY_NAME => 'Company Name',
            static::COLUMN_BUSINESS_UNIT_NAME => 'Business Unit Name',
            static::COLUMN_COMPANY_USER_NAME => 'Company User Name',
        ]);

        $config->setSortable([
            SpyCompanyUserTableMap::COL_ID_COMPANY_USER,
            SpyCompanyTableMap::COL_NAME,
            SpyCompanyBusinessUnitTableMap::COL_NAME,
            SpyCustomerTableMap::COL_FIRST_NAME,
        ]);

        $config->setSearchable([
            SpyCompanyUserTableMap::COL_ID_COMPANY_USER,
            SpyCompanyTableMap::COL_NAME,
            SpyCompanyBusinessUnitTableMap::COL_NAME,
            SpyCustomerTableMap::COL_FIRST_NAME,
        ]);

        $config->addRawColumn(static::COLUMN_SELECTED);
        $config->setTableAttributes([
            'data-selectable' => [
                'moveToSelector' => '#companyUsersToBeAssigned',
                'inputSelector' => '#fileAttachment_companyUserIdsToBeAssigned',
                'counterHolderSelector' => 'a[href="#tab-content-company-users-to-be-attached"]',
                'colId' => 'id_company_user',
            ],
        ]);
        $config->setFooter([]);

        $config->setUrl(Url::generate('/available-company-user-table', [
            FileAbstractController::REQUEST_PARAM_ID_FILE => $this->idFile,
        ])->build());

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array<array<string, mixed>>
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $queryResults = $this->runQuery($this->prepareQuery(), $config);

        $results = [];
        foreach ($queryResults as $row) {
            $mappedRow = [
                SpyCompanyUserTableMap::COL_ID_COMPANY_USER => $row[SpyCompanyUserTableMap::COL_ID_COMPANY_USER],
                static::COLUMN_COMPANY_NAME => $row[SpyCompanyTableMap::COL_NAME],
                static::COLUMN_BUSINESS_UNIT_NAME => $row[SpyCompanyBusinessUnitTableMap::COL_NAME],
                static::COLUMN_COMPANY_USER_NAME => trim(($row[SpyCustomerTableMap::COL_FIRST_NAME] ?? '') . ' ' . ($row[SpyCustomerTableMap::COL_LAST_NAME] ?? '')),
            ];
            $results[] = $this->formatRow($mappedRow);
        }

        return $results;
    }

    protected function prepareQuery(): SpyCompanyUserQuery
    {
        return $this->companyUserQuery
            ->joinWithCompany()
            ->joinWithCompanyBusinessUnit()
            ->joinWithCustomer()
            ->leftJoinSpyCompanyUserFile()
            ->addJoinCondition('SpyCompanyUserFile', 'SpyCompanyUserFile.FkFile = ?', $this->idFile)
            ->where('SpyCompanyUserFile.FkCompanyUser IS NULL')
            ->select([
                SpyCompanyUserTableMap::COL_ID_COMPANY_USER,
                SpyCompanyTableMap::COL_NAME,
                SpyCompanyBusinessUnitTableMap::COL_NAME,
                SpyCustomerTableMap::COL_FIRST_NAME,
                SpyCustomerTableMap::COL_LAST_NAME,
            ]);
    }

    /**
     * @param array<string, mixed> $companyUserEntity
     *
     * @return array<string, mixed>
     */
    protected function formatRow(array $companyUserEntity): array
    {
        return [
            static::COLUMN_SELECTED => $this->buildLinks($companyUserEntity),
            static::COLUMN_ID => $companyUserEntity[SpyCompanyUserTableMap::COL_ID_COMPANY_USER] ?? null,
            static::COLUMN_COMPANY_NAME => $companyUserEntity[static::COLUMN_COMPANY_NAME] ?? null,
            static::COLUMN_BUSINESS_UNIT_NAME => $companyUserEntity[static::COLUMN_BUSINESS_UNIT_NAME] ?? null,
            static::COLUMN_COMPANY_USER_NAME => $companyUserEntity[static::COLUMN_COMPANY_USER_NAME] ?? null,
        ];
    }

    /**
     * @param array<string, mixed> $companyUserEntity
     *
     * @return string
     */
    protected function buildLinks(array $companyUserEntity): string
    {
        $companyUserId = $companyUserEntity[SpyCompanyUserTableMap::COL_ID_COMPANY_USER] ?? '';
        $companyName = htmlspecialchars($companyUserEntity[static::COLUMN_COMPANY_NAME] ?? '');
        $businessUnitName = htmlspecialchars($companyUserEntity[static::COLUMN_BUSINESS_UNIT_NAME] ?? '');
        $companyUserName = htmlspecialchars($companyUserEntity[static::COLUMN_COMPANY_USER_NAME] ?? '');

        return '<input type="checkbox" name="companyUserIds[]" value="' . $companyUserId . '" class="js-selectable-table-checkbox" data-company-name="' . $companyName . '" data-business-unit-name="' . $businessUnitName . '" data-company-user-name="' . $companyUserName . '">';
    }
}
