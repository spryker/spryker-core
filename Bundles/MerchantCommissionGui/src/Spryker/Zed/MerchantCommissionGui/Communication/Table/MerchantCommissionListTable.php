<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionGui\Communication\Table;

use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\MerchantCommissionGui\Dependency\Service\MerchantCommissionGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class MerchantCommissionListTable extends AbstractTable
{
    /**
     * @uses \Orm\Zed\MerchantCommission\Persistence\Map\SpyMerchantCommissionTableMap::COL_ID_MERCHANT_COMMISSION
     *
     * @var string
     */
    protected const COL_ID_MERCHANT_COMMISSION = 'spy_merchant_commission.id_merchant_commission';

    /**
     * @uses \Orm\Zed\MerchantCommission\Persistence\Map\SpyMerchantCommissionTableMap::COL_NAME
     *
     * @var string
     */
    protected const COL_NAME = 'spy_merchant_commission.name';

    /**
     * @var string
     */
    protected const COL_PERIOD = 'period';

    /**
     * @uses \Orm\Zed\MerchantCommission\Persistence\Map\SpyMerchantCommissionTableMap::COL_IS_ACTIVE
     *
     * @var string
     */
    protected const COL_IS_ACTIVE = 'spy_merchant_commission.is_active';

    /**
     * @uses \Orm\Zed\MerchantCommission\Persistence\Map\SpyMerchantCommissionTableMap::COL_PRIORITY
     *
     * @var string
     */
    protected const COL_PRIORITY = 'spy_merchant_commission.priority';

    /**
     * @var string
     */
    protected const COL_GROUP_NAME = 'group_name';

    /**
     * @var string
     */
    protected const COL_STORE = 'store';

    /**
     * @var string
     */
    protected const COL_ACTIONS = 'actions';

    /**
     * @uses \Orm\Zed\MerchantCommission\Persistence\Map\SpyMerchantCommissionGroupTableMap::COL_NAME
     *
     * @var string
     */
    protected const COL_MERCHANT_COMMISSION_GROUP_NAME = 'spy_merchant_commission_group.name';

    /**
     * @uses \Orm\Zed\MerchantCommission\Persistence\Map\SpyMerchantCommissionTableMap::COL_VALID_FROM
     *
     * @var string
     */
    protected const COL_VALID_FROM = 'spy_merchant_commission.valid_from';

    /**
     * @uses \Orm\Zed\MerchantCommission\Persistence\Map\SpyMerchantCommissionTableMap::COL_VALID_TO
     *
     * @var string
     */
    protected const COL_VALID_TO = 'spy_merchant_commission.valid_to';

    /**
     * @var string
     */
    protected const URL_TABLE_DATA = '/table-data';

    /**
     * @uses \Spryker\Zed\MerchantCommissionGui\Communication\Controller\ViewController::indexAction()
     *
     * @var string
     */
    protected const URL_MERCHANT_COMMISSION_VIEW = '/merchant-commission-gui/view';

    /**
     * @uses \Spryker\Zed\MerchantCommissionGui\Communication\Controller\ViewController::REQUEST_PARAM_ID_MERCHANT_COMMISSION
     *
     * @var string
     */
    protected const REQUEST_PARAM_ID_MERCHANT_COMMISSION = 'id-merchant-commission';

    /**
     * @var \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionQuery
     */
    protected SpyMerchantCommissionQuery $merchantCommissionQuery;

    /**
     * @var \Spryker\Zed\MerchantCommissionGui\Dependency\Service\MerchantCommissionGuiToUtilDateTimeServiceInterface
     */
    protected MerchantCommissionGuiToUtilDateTimeServiceInterface $utilDateTimeService;

    /**
     * @param \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionQuery $merchantCommissionQuery
     * @param \Spryker\Zed\MerchantCommissionGui\Dependency\Service\MerchantCommissionGuiToUtilDateTimeServiceInterface $utilDateTimeService
     */
    public function __construct(
        SpyMerchantCommissionQuery $merchantCommissionQuery,
        MerchantCommissionGuiToUtilDateTimeServiceInterface $utilDateTimeService
    ) {
        $this->merchantCommissionQuery = $merchantCommissionQuery;
        $this->utilDateTimeService = $utilDateTimeService;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config = $this->configureHeader($config);
        $config = $this->configureSortableColumns($config);
        $config = $this->configureSearchableColumns($config);
        $config = $this->configureRawColumns($config);

        $config->setUrl($this->getTableUrl());
        $config->setHasSearchableFieldsWithAggregateFunctions(true);
        $config->setDefaultSortField(static::COL_ID_MERCHANT_COMMISSION, TableConfiguration::SORT_DESC);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configureHeader(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COL_ID_MERCHANT_COMMISSION => 'ID',
            static::COL_NAME => 'Name',
            static::COL_PERIOD => 'Period',
            static::COL_IS_ACTIVE => 'Status',
            static::COL_PRIORITY => 'Priority',
            static::COL_GROUP_NAME => 'Group',
            static::COL_STORE => 'Store',
            static::COL_ACTIONS => 'Actions',
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $configuration
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configureSortableColumns(TableConfiguration $configuration): TableConfiguration
    {
        $configuration->setSortable([
            static::COL_ID_MERCHANT_COMMISSION,
            static::COL_NAME,
            static::COL_IS_ACTIVE,
            static::COL_PRIORITY,
            static::COL_GROUP_NAME,
        ]);

        return $configuration;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $configuration
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configureSearchableColumns(TableConfiguration $configuration): TableConfiguration
    {
        $configuration->setSearchable([
            static::COL_NAME,
            static::COL_GROUP_NAME,
        ]);

        return $configuration;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configureRawColumns(TableConfiguration $config): TableConfiguration
    {
        $config->setRawColumns([
            static::COL_IS_ACTIVE,
            static::COL_STORE,
            static::COL_ACTIONS,
        ]);

        return $config;
    }

    /**
     * @return string
     */
    protected function getTableUrl(): string
    {
        return Url::generate(
            static::URL_TABLE_DATA,
            $this->getRequest()->query->all(),
        );
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array<mixed>
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $queryResults = $this->runQuery($this->prepareQuery(), $config);

        $rows = [];
        foreach ($queryResults as $rowData) {
            $rows[] = $this->formatRowData($rowData);
        }

        return $rows;
    }

    /**
     * @return \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionQuery
     */
    protected function prepareQuery(): SpyMerchantCommissionQuery
    {
        /** @var \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionQuery $merchantCommissionQuery */
        $merchantCommissionQuery = $this->merchantCommissionQuery
            ->groupByIdMerchantCommission()
            ->innerJoinMerchantCommissionGroup()
            ->useMerchantCommissionStoreQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinStore('store')
            ->endUse()
            ->withColumn(static::COL_MERCHANT_COMMISSION_GROUP_NAME, static::COL_GROUP_NAME)
            ->withColumn(sprintf('GROUP_CONCAT(%s)', 'store.name'), static::COL_STORE);

        return $merchantCommissionQuery;
    }

    /**
     * @param array<string, mixed> $rowData
     *
     * @return array<string, mixed>
     */
    protected function formatRowData(array $rowData): array
    {
        return [
            static::COL_ID_MERCHANT_COMMISSION => $rowData[static::COL_ID_MERCHANT_COMMISSION],
            static::COL_NAME => $rowData[static::COL_NAME],
            static::COL_PERIOD => $this->formatPeriod($rowData),
            static::COL_IS_ACTIVE => $this->formatStatusLabel($rowData[static::COL_IS_ACTIVE]),
            static::COL_PRIORITY => $rowData[static::COL_PRIORITY],
            static::COL_GROUP_NAME => $rowData[static::COL_GROUP_NAME],
            static::COL_STORE => $rowData[static::COL_STORE],
            static::COL_ACTIONS => $this->getActionsColumnData($rowData),
        ];
    }

    /**
     * @param array<string, mixed> $rowData
     *
     * @return string
     */
    protected function formatPeriod(array $rowData): string
    {
        return sprintf(
            '%s - %s',
            $this->utilDateTimeService->formatDateTime($rowData[static::COL_VALID_FROM]),
            $this->utilDateTimeService->formatDateTime($rowData[static::COL_VALID_TO]),
        );
    }

    /**
     * @param bool $isActive
     *
     * @return string
     */
    protected function formatStatusLabel(bool $isActive): string
    {
        return $isActive
            ? $this->generateLabel('Active', 'label-info')
            : $this->generateLabel('Inactive', 'label-danger');
    }

    /**
     * @param array<string, mixed> $rowData
     *
     * @return string
     */
    protected function getActionsColumnData(array $rowData): string
    {
        return $this->generateViewButton(
            Url::generate(static::URL_MERCHANT_COMMISSION_VIEW, [
                static::REQUEST_PARAM_ID_MERCHANT_COMMISSION => $rowData[static::COL_ID_MERCHANT_COMMISSION],
            ]),
            'View',
        );
    }
}
