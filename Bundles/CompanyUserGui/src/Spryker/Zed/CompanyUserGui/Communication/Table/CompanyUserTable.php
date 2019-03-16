<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Table;

use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\CompanyUserGui\Communication\Table\PluginExecutor\CompanyUserTableExpanderPluginExecutorInterface;
use Spryker\Zed\CompanyUserGui\CompanyUserGuiConfig;
use Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserTableDeleteActionPluginInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class CompanyUserTable extends AbstractTable
{
    protected const COL_COMPANY_NAME = 'company_name';
    protected const COL_COMPANY_USER_NAME = 'company_user_name';
    protected const COL_IS_ACTIVE = 'is_active';
    protected const COL_COMPANY_USER_ACTIONS = 'actions';

    /**
     * @var \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    protected $companyUserQuery;

    /**
     * @var \Spryker\Zed\CompanyUserGui\Communication\Table\PluginExecutor\CompanyUserTableExpanderPluginExecutorInterface
     */
    protected $companyUserTableExpanderPluginExecutor;

    /**
     * @var \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserTableActionExpanderPluginInterface[]
     */
    protected $companyUserTableActionLinksExpanderPlugins;

    /**
     * @var null|\Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserTableDeleteActionPluginInterface
     */
    protected $companyUserTableDeleteActionPlugin;

    /**
     * @param \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery $companyUserQuery
     * @param \Spryker\Zed\CompanyUserGui\Communication\Table\PluginExecutor\CompanyUserTableExpanderPluginExecutorInterface $companyUserTableExpanderPluginExecutor
     * @param \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserTableActionExpanderPluginInterface[] $companyUserTableActionLinksExpanderPlugins
     * @param \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserTableDeleteActionPluginInterface|null $companyUserTableDeleteActionPlugin
     */
    public function __construct(
        SpyCompanyUserQuery $companyUserQuery,
        CompanyUserTableExpanderPluginExecutorInterface $companyUserTableExpanderPluginExecutor,
        array $companyUserTableActionLinksExpanderPlugins,
        ?CompanyUserTableDeleteActionPluginInterface $companyUserTableDeleteActionPlugin
    ) {
        $this->companyUserQuery = $companyUserQuery;
        $this->companyUserTableExpanderPluginExecutor = $companyUserTableExpanderPluginExecutor;
        $this->companyUserTableActionLinksExpanderPlugins = $companyUserTableActionLinksExpanderPlugins;
        $this->companyUserTableDeleteActionPlugin = $companyUserTableDeleteActionPlugin;
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
        $actionButtons = [
            $this->generateCompanyUserEditButton($companyUserDataItem),
            $this->generateCompanyUserStatusChangeButton($companyUserDataItem),
            $this->generateCompanyUserDeleteButton($companyUserDataItem),
        ];

        $actionButtons = $this->expandLinks($companyUserDataItem, $actionButtons);

        return implode(' ', $actionButtons);
    }

    /**
     * @param array $companyUserDataItem
     *
     * @return string
     */
    protected function generateCompanyUserStatusLabel(array $companyUserDataItem): string
    {
        if ($companyUserDataItem[SpyCompanyUserTableMap::COL_IS_ACTIVE]) {
            return $this->generateLabel('Active', 'label-info');
        }

        return $this->generateLabel('Disabled', 'label-danger');
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
                CompanyUserGuiConfig::PARAM_ID_COMPANY_USER => $companyUserDataItem[SpyCompanyUserTableMap::COL_ID_COMPANY_USER],
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
                    CompanyUserGuiConfig::PARAM_ID_COMPANY_USER => $companyUserDataItem[SpyCompanyUserTableMap::COL_ID_COMPANY_USER],
                ]),
                'Disable'
            );
        }

        return $this->generateViewButton(
            Url::generate(CompanyUserGuiConfig::URL_ENABLE_COMPANY_USER, [
                CompanyUserGuiConfig::PARAM_ID_COMPANY_USER => $companyUserDataItem[SpyCompanyUserTableMap::COL_ID_COMPANY_USER],
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
        $deleteUrl = CompanyUserGuiConfig::URL_CONFIRM_DELETE_COMPANY_USER;

        if ($this->companyUserTableDeleteActionPlugin !== null) {
            $deleteUrl = $this->companyUserTableDeleteActionPlugin->getPath();
        }

        return $this->generateRemoveButton(
            Url::generate($deleteUrl, [
                CompanyUserGuiConfig::PARAM_ID_COMPANY_USER => $companyUserDataItem[SpyCompanyUserTableMap::COL_ID_COMPANY_USER],
            ]),
            'Delete'
        );
    }

    /**
     * @param array $companyUserDataItem
     * @param string[] $buttons
     *
     * @return string[]
     */
    protected function expandLinks(array $companyUserDataItem, array $buttons): array
    {
        foreach ($this->companyUserTableActionLinksExpanderPlugins as $companyUserTableActionExpanderPlugin) {
            $buttonTransfer = $companyUserTableActionExpanderPlugin->expand($companyUserDataItem, $buttons);

            $buttons[] = $this->generateButton(
                $buttonTransfer->getUrl(),
                $buttonTransfer->getTitle(),
                $buttonTransfer->getDefaultOptions(),
                $buttonTransfer->getCustomOptions()
            );
        }

        return $buttons;
    }
}
