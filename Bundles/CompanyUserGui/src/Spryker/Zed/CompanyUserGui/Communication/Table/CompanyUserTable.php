<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Table;

use Generated\Shared\Transfer\ButtonTransfer;
use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\CompanyUserGui\Communication\Table\PluginExecutor\CompanyUserTableExpanderPluginExecutorInterface;
use Spryker\Zed\CompanyUserGui\CompanyUserGuiConfig;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class CompanyUserTable extends AbstractTable
{
    protected const COL_COMPANY_NAME = 'company_name';
    protected const COL_COMPANY_USER_NAME = 'company_user_name';
    protected const COL_IS_ACTIVE = 'is_active';
    protected const COL_COMPANY_USER_ACTIONS = 'actions';

    protected const BUTTON_EDIT_TITLE = 'Edit';
    protected const BUTTON_DISABLE_TITLE = 'Disable';
    protected const BUTTON_ENABLE_TITLE = 'Enable';
    protected const BUTTON_DELETE_TITLE = 'Delete';

    /**
     * @var \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    protected $companyUserQuery;

    /**
     * @var \Spryker\Zed\CompanyUserGui\Communication\Table\PluginExecutor\CompanyUserTableExpanderPluginExecutorInterface
     */
    protected $companyUserTableExpanderPluginExecutor;

    /**
     * @param \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery $companyUserQuery
     * @param \Spryker\Zed\CompanyUserGui\Communication\Table\PluginExecutor\CompanyUserTableExpanderPluginExecutorInterface $companyUserTableExpanderPluginExecutor
     */
    public function __construct(
        SpyCompanyUserQuery $companyUserQuery,
        CompanyUserTableExpanderPluginExecutorInterface $companyUserTableExpanderPluginExecutor
    ) {
        $this->companyUserQuery = $companyUserQuery;
        $this->companyUserTableExpanderPluginExecutor = $companyUserTableExpanderPluginExecutor;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            CompanyUserGuiConfig::COL_ID_COMPANY_USER => 'User ID',
            static::COL_COMPANY_NAME => 'Company Name',
            static::COL_COMPANY_USER_NAME => 'User Name',
        ]);

        $config->setSearchable([
            SpyCompanyUserTableMap::COL_ID_COMPANY_USER,
            SpyCompanyTableMap::COL_NAME,
            SpyCustomerTableMap::COL_FIRST_NAME,
            SpyCustomerTableMap::COL_LAST_NAME,
        ]);

        $config->setSortable([
            CompanyUserGuiConfig::COL_ID_COMPANY_USER,
            static::COL_COMPANY_NAME,
            static::COL_COMPANY_USER_NAME,
        ]);

        $config->setRawColumns([
            static::COL_IS_ACTIVE,
            static::COL_COMPANY_USER_ACTIONS,
        ]);

        $config = $this->companyUserTableExpanderPluginExecutor->executeConfigExpanderPlugins($config);

        $config = $this->addStatusConfigHeader($config);
        $config = $this->addActionsConfigHeader($config);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function addStatusConfigHeader(TableConfiguration $config): TableConfiguration
    {
        $configHeader = $config->getHeader();
        $configHeader += [
            static::COL_IS_ACTIVE => 'Status',
        ];
        $config->setHeader($configHeader);

        $configSortable = $config->getSortable();
        $configSortable[] = static::COL_IS_ACTIVE;

        $config->setSortable($configSortable);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function addActionsConfigHeader(TableConfiguration $config): TableConfiguration
    {
        $configHeader = $config->getHeader();
        $configHeader += [
            static::COL_COMPANY_USER_ACTIONS => 'Actions',
        ];
        $config->setHeader($configHeader);

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
            $companyUserDataTableRow = $this->companyUserTableExpanderPluginExecutor->executePrepareDataExpanderPlugins(
                $this->mapCompanyUserDataItemToCompanyUserTableDataRow($companyUserDataItem)
            );

            $companyUserDataTableRows[] = $companyUserDataTableRow;
        }

        return $companyUserDataTableRows;
    }

    /**
     * @param array $companyUserDataItem
     *
     * @return array
     */
    protected function mapCompanyUserDataItemToCompanyUserTableDataRow(array $companyUserDataItem): array
    {
        return [
            CompanyUserGuiConfig::COL_ID_COMPANY_USER => $companyUserDataItem[SpyCompanyUserTableMap::COL_ID_COMPANY_USER],
            static::COL_COMPANY_USER_NAME => $companyUserDataItem[static::COL_COMPANY_USER_NAME],
            static::COL_COMPANY_NAME => $companyUserDataItem[static::COL_COMPANY_NAME],
            static::COL_IS_ACTIVE => $this->generateCompanyUserStatusLabel($companyUserDataItem),
            static::COL_COMPANY_USER_ACTIONS => $this->buildLinks($companyUserDataItem),
        ];
    }

    /**
     * @module Customer
     * @module Company
     *
     * @param \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery $companyUserQuery
     *
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    protected function prepareQuery(SpyCompanyUserQuery $companyUserQuery): SpyCompanyUserQuery
    {
        return $companyUserQuery->joinCustomer()
            ->useCustomerQuery()
                ->filterByAnonymizedAt(null, Criteria::EQUAL)
            ->endUse()
            ->withColumn(
                'CONCAT(' . SpyCustomerTableMap::COL_FIRST_NAME . ', \' \', ' . SpyCustomerTableMap::COL_LAST_NAME . ')',
                static::COL_COMPANY_USER_NAME
            )
            ->joinCompany()
            ->withColumn(SpyCompanyTableMap::COL_NAME, static::COL_COMPANY_NAME);
    }

    /**
     * @param array $companyUserDataItem
     *
     * @return string
     */
    protected function buildLinks(array $companyUserDataItem): string
    {
        $buttons = [];

        foreach ($this->getAllButtonTransfers($companyUserDataItem) as $buttonTransfer) {
            $buttons[] = $this->generateButton(
                $buttonTransfer->getUrl(),
                $buttonTransfer->getTitle(),
                $buttonTransfer->getDefaultOptions(),
                $buttonTransfer->getCustomOptions()
            );
        }

        return implode(' ', $buttons);
    }

    /**
     * @param array $companyUserDataItem
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    protected function getAllButtonTransfers(array $companyUserDataItem): array
    {
        $buttonTransfers = [
            $this->generateCompanyUserEditButton($companyUserDataItem),
            $this->generateCompanyUserStatusChangeButton($companyUserDataItem),
            $this->generateCompanyUserDeleteButton($companyUserDataItem),
        ];

        return $this->expandActionLinks($companyUserDataItem, $buttonTransfers);
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
     * @return \Generated\Shared\Transfer\ButtonTransfer
     */
    protected function generateCompanyUserEditButton(array $companyUserDataItem): ButtonTransfer
    {
        $defaultOptions = [
            'class' => 'btn-edit',
            'icon' => 'fa-pencil-square-o',
        ];
        $url = Url::generate(CompanyUserGuiConfig::URL_EDIT_COMPANY_USER, [
            CompanyUserGuiConfig::PARAM_ID_COMPANY_USER => $companyUserDataItem[SpyCompanyUserTableMap::COL_ID_COMPANY_USER],
        ]);

        return $this->generateButtonTransfer($url, static::BUTTON_EDIT_TITLE, $defaultOptions);
    }

    /**
     * @param array $companyUserDataItem
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer
     */
    protected function generateCompanyUserStatusChangeButton(array $companyUserDataItem): ButtonTransfer
    {
        if ($companyUserDataItem[SpyCompanyUserTableMap::COL_IS_ACTIVE]) {
            $defaultOptions = [
                'class' => 'safe-submit btn-danger',
                'icon' => 'fa-trash',
            ];
            $url = Url::generate(CompanyUserGuiConfig::URL_DISABLE_COMPANY_USER, [
                CompanyUserGuiConfig::PARAM_ID_COMPANY_USER => $companyUserDataItem[SpyCompanyUserTableMap::COL_ID_COMPANY_USER],
            ]);

            return $this->generateButtonTransfer($url, static::BUTTON_DISABLE_TITLE, $defaultOptions);
        }

        $defaultOptions = [
            'class' => 'btn-view',
            'icon' => 'fa-caret-right',
        ];
        $url = Url::generate(CompanyUserGuiConfig::URL_ENABLE_COMPANY_USER, [
            CompanyUserGuiConfig::PARAM_ID_COMPANY_USER => $companyUserDataItem[SpyCompanyUserTableMap::COL_ID_COMPANY_USER],
        ]);

        return $this->generateButtonTransfer($url, static::BUTTON_ENABLE_TITLE, $defaultOptions);
    }

    /**
     * @param array $companyUserDataItem
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer
     */
    protected function generateCompanyUserDeleteButton(array $companyUserDataItem): ButtonTransfer
    {
        $defaultOptions = [
            'class' => 'safe-submit btn-danger',
            'icon' => 'fa-trash',
        ];
        $url = Url::generate(CompanyUserGuiConfig::URL_CONFIRM_DELETE_COMPANY_USER, [
            CompanyUserGuiConfig::PARAM_ID_COMPANY_USER => $companyUserDataItem[SpyCompanyUserTableMap::COL_ID_COMPANY_USER],
        ]);

        return $this->generateButtonTransfer($url, static::BUTTON_DELETE_TITLE, $defaultOptions);
    }

    /**
     * @param string $url
     * @param string $title
     * @param array $defaultOptions
     * @param array|null $customOptions
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer
     */
    protected function generateButtonTransfer(string $url, string $title, array $defaultOptions, ?array $customOptions = null): ButtonTransfer
    {
        return (new ButtonTransfer())
            ->setUrl($url)
            ->setTitle($title)
            ->setDefaultOptions($defaultOptions)
            ->setCustomOptions($customOptions);
    }

    /**
     * @param array $companyUserDataItem
     * @param \Generated\Shared\Transfer\ButtonTransfer[] $buttonTransfers
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    protected function expandActionLinks(array $companyUserDataItem, array $buttonTransfers): array
    {
        return $this->companyUserTableExpanderPluginExecutor
            ->executeActionExpanderPlugins($companyUserDataItem, $buttonTransfers);
    }
}
