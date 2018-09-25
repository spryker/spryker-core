<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Table;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\CompanyUserGui\Communication\Table\PluginExecutor\CompanyUserTableConfigExpanderPluginExecutorInterface;
use Spryker\Zed\CompanyUserGui\Communication\Table\PluginExecutor\CompanyUserTablePrepareDataExpanderPluginExecutorInterface;
use Spryker\Zed\CompanyUserGui\CompanyUserGuiConfig;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class CompanyUserTable extends AbstractTable
{
    protected const COL_ID_COMPANY_USER = 'id_company_user';
    protected const COL_COMPANY_NAME = 'company_name';
    protected const COL_COMPANY_USER_NAME = 'company_user_name';
    protected const COL_COMPANY_USER_STATUS = 'company_user_status';
    protected const COL_COMPANY_USER_ACTIONS = 'actions';

    protected const REQUEST_ID_COMPANY_USER = 'id-company-user';

    /**
     * @var \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    protected $companyUserQuery;

    /**
     * @var \Spryker\Zed\CompanyUserGui\Communication\Table\PluginExecutor\CompanyUserTableConfigExpanderPluginExecutorInterface
     */
    protected $companyUserTableConfigExpanderPluginExecutor;

    /**
     * @var \Spryker\Zed\CompanyUserGui\Communication\Table\PluginExecutor\CompanyUserTablePrepareDataExpanderPluginExecutorInterface
     */
    protected $companyUserTablePrepareDataExpanderPluginExecutor;

    /**
     * @param \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery $companyUserQuery
     * @param \Spryker\Zed\CompanyUserGui\Communication\Table\PluginExecutor\CompanyUserTableConfigExpanderPluginExecutorInterface $companyUserTableConfigExpanderPluginExecutor
     * @param \Spryker\Zed\CompanyUserGui\Communication\Table\PluginExecutor\CompanyUserTablePrepareDataExpanderPluginExecutorInterface $companyUserTablePrepareDataExpanderPluginExecutor
     */
    public function __construct(
        SpyCompanyUserQuery $companyUserQuery,
        CompanyUserTableConfigExpanderPluginExecutorInterface $companyUserTableConfigExpanderPluginExecutor,
        CompanyUserTablePrepareDataExpanderPluginExecutorInterface $companyUserTablePrepareDataExpanderPluginExecutor
    ) {
        $this->companyUserQuery = $companyUserQuery;
        $this->companyUserTableConfigExpanderPluginExecutor = $companyUserTableConfigExpanderPluginExecutor;
        $this->companyUserTablePrepareDataExpanderPluginExecutor = $companyUserTablePrepareDataExpanderPluginExecutor;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            static::COL_ID_COMPANY_USER => 'User ID',
            static::COL_COMPANY_NAME => 'Company Name',
            static::COL_COMPANY_USER_NAME => 'User Name',
            static::COL_COMPANY_USER_STATUS => 'Status',
            static::COL_COMPANY_USER_ACTIONS => 'Actions',
        ]);

        $config->setSearchable([
            static::COL_COMPANY_NAME,
            static::COL_COMPANY_USER_NAME,
        ]);

        $config->setSortable([
            static::COL_ID_COMPANY_USER,
            static::COL_COMPANY_NAME,
            static::COL_COMPANY_USER_NAME,
            static::COL_COMPANY_USER_STATUS,
        ]);

        $config->setRawColumns([
            static::COL_COMPANY_USER_STATUS,
            static::COL_COMPANY_USER_ACTIONS,
        ]);

        $config = $this->companyUserTableConfigExpanderPluginExecutor->executeConfigExpanderPlugins($config);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $companyUserDataItems = $this->runQuery(
            $this->prepareQuery($this->companyUserQuery),
            $config
        );

        $companyUserDataTableRows = [];
        foreach ($companyUserDataItems as $companyUserDataItem) {
            $companyUserDataTableRow = $this->mapCompanyUserTransferToCompanyUserTableDataRow($companyUserDataItem);

            $companyUserDataTableRow += $this->companyUserTablePrepareDataExpanderPluginExecutor->executePrepareDataExpanderPlugins(
                $this->mapCompanyUserDataItemToCompanyUserTransfer($companyUserDataItem)
            );

            $companyUserDataTableRows[] = $companyUserDataTableRow;
        }

        return $companyUserDataTableRows;
    }

    /**
     * @param array $companyUserDataItem
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function mapCompanyUserDataItemToCompanyUserTransfer(array $companyUserDataItem): CompanyUserTransfer
    {
        return (new CompanyUserTransfer())
            ->fromArray(
                $this->normalizeCompanyUserDataItemArrayKeys($companyUserDataItem),
                true
            );
    }

    /**
     * @param array $companyUserData
     *
     * @return array
     */
    protected function normalizeCompanyUserDataItemArrayKeys(array $companyUserData): array
    {
        $processedCompanyUserData = [];
        foreach ($companyUserData as $key => $value) {
            $processedCompanyUserData += [
                str_replace(SpyCompanyUserTableMap::TABLE_NAME . '.', '', $key) => $value,
            ];
        }

        return $processedCompanyUserData;
    }

    /**
     * @param array $companyUserDataItem
     *
     * @return array
     */
    protected function mapCompanyUserTransferToCompanyUserTableDataRow(array $companyUserDataItem): array
    {
        return [
            static::COL_ID_COMPANY_USER => $companyUserDataItem[SpyCompanyUserTableMap::COL_ID_COMPANY_USER],
            static::COL_COMPANY_USER_NAME => $companyUserDataItem[static::COL_COMPANY_USER_NAME],
            static::COL_COMPANY_NAME => $companyUserDataItem[static::COL_COMPANY_NAME],
            static::COL_COMPANY_USER_STATUS => $this->generateCompanyUserStatusLabel($companyUserDataItem),
            static::COL_COMPANY_USER_ACTIONS => $this->buildLinks($companyUserDataItem),
        ];
    }

    /**
     * @param \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery $companyUserQuery
     *
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    protected function prepareQuery(SpyCompanyUserQuery $companyUserQuery): SpyCompanyUserQuery
    {
        $query = $companyUserQuery
            ->joinCustomer()
            ->withColumn(
                'CONCAT(' . SpyCustomerTableMap::COL_FIRST_NAME . ', \' \', ' . SpyCustomerTableMap::COL_LAST_NAME . ')',
                static::COL_COMPANY_USER_NAME
            )
            ->joinCompany()
            ->withColumn(SpyCompanyTableMap::COL_NAME, static::COL_COMPANY_NAME);

        return $query;
    }

    /**
     * @param array $companyUserDataItem
     *
     * @return string
     */
    protected function buildLinks(array $companyUserDataItem): string
    {
        $actionButtons = [
            $this->generateCompanyUserEditButton($companyUserDataItem),
            $this->generateCompanyUserStatusChangeButton($companyUserDataItem),
            $this->generateCompanyUserDeleteButton($companyUserDataItem),
        ];

        return implode($actionButtons);
    }

    /**
     * @param array $companyUserDataItem
     *
     * @return string
     */
    protected function generateCompanyUserStatusLabel(array $companyUserDataItem): string
    {
        if ($companyUserDataItem[SpyCompanyUserTableMap::COL_IS_ACTIVE]) {
            return '<span class="label label-info">Active</span>';
        }

        return '<span class="label label-danger">Disabled</span>';
    }

    /**
     * @param array $companyUserDataItem
     *
     * @return string
     */
    protected function generateCompanyUserEditButton(array $companyUserDataItem): string
    {
        return $this->generateEditButton(
            Url::generate(CompanyUserGuiConfig::URL_EDIT_COMPANY_USER, [
                static::REQUEST_ID_COMPANY_USER => $companyUserDataItem[SpyCompanyUserTableMap::COL_ID_COMPANY_USER],
            ]),
            'Edit'
        );
    }

    /**
     * @param array $companyUserDataItem
     *
     * @return string
     */
    protected function generateCompanyUserStatusChangeButton(array $companyUserDataItem): string
    {
        if ($companyUserDataItem[SpyCompanyUserTableMap::COL_IS_ACTIVE]) {
            return $this->generateRemoveButton(
                Url::generate(CompanyUserGuiConfig::URL_DISABLE_COMPANY_USER, [
                    static::REQUEST_ID_COMPANY_USER => $companyUserDataItem[SpyCompanyUserTableMap::COL_ID_COMPANY_USER],
                ]),
                'Disable'
            );
        }

        return $this->generateViewButton(
            Url::generate(CompanyUserGuiConfig::URL_ENABLE_COMPANY_USER, [
                static::REQUEST_ID_COMPANY_USER => $companyUserDataItem[SpyCompanyUserTableMap::COL_ID_COMPANY_USER],
            ]),
            'Enable'
        );
    }

    /**
     * @param array $companyUserDataItem
     *
     * @return string
     */
    protected function generateCompanyUserDeleteButton(array $companyUserDataItem): string
    {
        return $this->generateRemoveButton(
            Url::generate(CompanyUserGuiConfig::URL_DELETE_COMPANY_USER, [
                static::REQUEST_ID_COMPANY_USER => $companyUserDataItem[SpyCompanyUserTableMap::COL_ID_COMPANY_USER],
            ]),
            'Delete'
        );
    }
}
