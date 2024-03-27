<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestGui\Communication\Table;

use Generated\Shared\Transfer\MerchantRelationRequestConditionsTransfer;
use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\MerchantRelationRequest\Persistence\Map\SpyMerchantRelationRequestTableMap;
use Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\MerchantRelationRequestGui\Dependency\Service\MerchantRelationRequestGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\MerchantRelationRequestGui\MerchantRelationRequestGuiConfig;

class MerchantRelationRequestListTable extends AbstractTable
{
    /**
     * @var string
     */
    public const PARAM_ID_MERCHANT_RELATION_REQUEST = 'id-merchant-relation-request';

    /**
     * @var string
     */
    protected const COL_MERCHANT_NAME = 'merchant_name';

    /**
     * @var string
     */
    protected const COL_ID_MERCHANT = 'id_merchant';

    /**
     * @var string
     */
    protected const COL_COMPANY_NAME = 'company_name';

    /**
     * @var string
     */
    protected const COL_BUSINESS_UNIT_OWNER_NAME = 'business_unit_owner_name';

    /**
     * @var string
     */
    protected const COL_ASSIGNEE_COMPANY_BUSINESS_UNIT_NAMES = 'assignee_company_business_unit_names';

    /**
     * @var string
     */
    protected const COL_ACTIONS = 'actions';

    /**
     * @uses \Spryker\Zed\MerchantRelationRequestGui\Communication\Controller\ListController::tableDataAction()
     *
     * @var string
     */
    protected const URL_TABLE_DATA = '/table-data';

    /**
     * @uses \Spryker\Zed\MerchantRelationRequestGui\Communication\Controller\EditController::indexAction()
     *
     * @var string
     */
    protected const URL_MERCHANT_RELATIONSHIP_REQUEST_EDIT = '/merchant-relation-request-gui/edit';

    /**
     * @var string
     */
    protected const STATUS_LABEL_DEFAULT = 'label';

    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_PENDING
     *
     * @var string
     */
    protected const STATUS_PENDING = 'pending';

    /**
     * @var \Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestQuery
     */
    protected SpyMerchantRelationRequestQuery $merchantRelationRequestQuery;

    /**
     * @var \Spryker\Zed\MerchantRelationRequestGui\Dependency\Service\MerchantRelationRequestGuiToUtilDateTimeServiceInterface
     */
    protected MerchantRelationRequestGuiToUtilDateTimeServiceInterface $dateTimeService;

    /**
     * @var \Spryker\Zed\MerchantRelationRequestGui\MerchantRelationRequestGuiConfig
     */
    protected MerchantRelationRequestGuiConfig $merchantRelationRequestGuiConfig;

    /**
     * @var \Generated\Shared\Transfer\MerchantRelationRequestConditionsTransfer
     */
    protected MerchantRelationRequestConditionsTransfer $merchantRelationRequestConditionsTransfer;

    /**
     * @param \Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestQuery $merchantRelationRequestQuery
     * @param \Spryker\Zed\MerchantRelationRequestGui\Dependency\Service\MerchantRelationRequestGuiToUtilDateTimeServiceInterface $dateTimeService
     * @param \Spryker\Zed\MerchantRelationRequestGui\MerchantRelationRequestGuiConfig $merchantRelationRequestGuiConfig
     * @param \Generated\Shared\Transfer\MerchantRelationRequestConditionsTransfer $merchantRelationRequestConditionsTransfer
     */
    public function __construct(
        SpyMerchantRelationRequestQuery $merchantRelationRequestQuery,
        MerchantRelationRequestGuiToUtilDateTimeServiceInterface $dateTimeService,
        MerchantRelationRequestGuiConfig $merchantRelationRequestGuiConfig,
        MerchantRelationRequestConditionsTransfer $merchantRelationRequestConditionsTransfer
    ) {
        $this->merchantRelationRequestQuery = $merchantRelationRequestQuery;
        $this->dateTimeService = $dateTimeService;
        $this->merchantRelationRequestGuiConfig = $merchantRelationRequestGuiConfig;
        $this->merchantRelationRequestConditionsTransfer = $merchantRelationRequestConditionsTransfer;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config = $this->setHeader($config);
        $config = $this->setSortable($config);
        $config = $this->setSearchable($config);
        $config = $this->setRawColumns($config);

        $config->setUrl($this->getTableUrl());
        $config->setHasSearchableFieldsWithAggregateFunctions(true);
        $config->setDefaultSortField(SpyMerchantRelationRequestTableMap::COL_ID_MERCHANT_RELATION_REQUEST, TableConfiguration::SORT_DESC);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setHeader(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            SpyMerchantRelationRequestTableMap::COL_ID_MERCHANT_RELATION_REQUEST => 'ID',
            SpyMerchantTableMap::COL_NAME => 'Merchant name',
            SpyCompanyTableMap::COL_NAME => 'Company',
            SpyCompanyBusinessUnitTableMap::COL_NAME => 'Business Unit Owner',
            static::COL_ASSIGNEE_COMPANY_BUSINESS_UNIT_NAMES => 'Assigned Business Units',
            SpyMerchantRelationRequestTableMap::COL_CREATED_AT => 'Created',
            SpyMerchantRelationRequestTableMap::COL_STATUS => 'Status',
            static::COL_ACTIONS => 'Actions',
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setSortable(TableConfiguration $config): TableConfiguration
    {
        $config->setSortable([
            SpyMerchantRelationRequestTableMap::COL_ID_MERCHANT_RELATION_REQUEST,
            SpyMerchantTableMap::COL_NAME,
            SpyCompanyTableMap::COL_NAME,
            SpyCompanyBusinessUnitTableMap::COL_NAME,
            SpyMerchantRelationRequestTableMap::COL_CREATED_AT,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setSearchable(TableConfiguration $config): TableConfiguration
    {
        $config->setSearchable([
            SpyMerchantTableMap::COL_NAME,
            SpyCompanyTableMap::COL_NAME,
            SpyCompanyBusinessUnitTableMap::COL_NAME,
            sprintf('GROUP_CONCAT(DISTINCT %s)', 'assigneeCompanyBusinessUnits.name'),
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setRawColumns(TableConfiguration $config): TableConfiguration
    {
        $config->setRawColumns([
            SpyMerchantRelationRequestTableMap::COL_STATUS,
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

        foreach ($queryResults as $item) {
            $rows[] = $this->getRowData($item);
        }

        return $rows;
    }

    /**
     * @param array<string, mixed> $item
     *
     * @return array<string, mixed>
     */
    protected function getRowData(array $item): array
    {
        $rowData = [
            SpyMerchantRelationRequestTableMap::COL_ID_MERCHANT_RELATION_REQUEST => $item[SpyMerchantRelationRequestTableMap::COL_ID_MERCHANT_RELATION_REQUEST],
            SpyMerchantTableMap::COL_NAME => $this->getMerchantNameColumnData($item),
            SpyCompanyTableMap::COL_NAME => $item[static::COL_COMPANY_NAME],
            SpyCompanyBusinessUnitTableMap::COL_NAME => $item[static::COL_BUSINESS_UNIT_OWNER_NAME],
            static::COL_ASSIGNEE_COMPANY_BUSINESS_UNIT_NAMES => $item[static::COL_ASSIGNEE_COMPANY_BUSINESS_UNIT_NAMES],
            SpyMerchantRelationRequestTableMap::COL_CREATED_AT => $this->dateTimeService
                ->formatDateTime($item[SpyMerchantRelationRequestTableMap::COL_CREATED_AT]),
            SpyMerchantRelationRequestTableMap::COL_STATUS => $this->getStatusLabel($item[SpyMerchantRelationRequestTableMap::COL_STATUS]),
            static::COL_ACTIONS => $this->getActionsColumnData($item),
        ];

        return $rowData;
    }

    /**
     * @module Company
     * @module CompanyBusinessUnit
     * @module Merchant
     *
     * @return \Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestQuery
     */
    protected function prepareQuery(): SpyMerchantRelationRequestQuery
    {
        /** @var \Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestQuery $merchantRelationRequestQuery */
        $merchantRelationRequestQuery = $this->merchantRelationRequestQuery;

        // @phpstan-ignore-next-line
        $merchantRelationRequestQuery
            ->groupByIdMerchantRelationRequest()
            ->innerJoinMerchant()
            ->innerJoinCompanyBusinessUnit()
            ->useCompanyBusinessUnitQuery()
                ->innerJoinCompany()
            ->endUse()
            ->useSpyMerchantRelationRequestToCompanyBusinessUnitQuery()
                ->innerJoinCompanyBusinessUnit('assigneeCompanyBusinessUnits')
            ->endUse()
            ->withColumn(SpyMerchantTableMap::COL_NAME, static::COL_MERCHANT_NAME)
            ->withColumn(SpyMerchantTableMap::COL_ID_MERCHANT, static::COL_ID_MERCHANT)
            ->withColumn(SpyCompanyTableMap::COL_NAME, static::COL_COMPANY_NAME)
            ->withColumn(SpyCompanyBusinessUnitTableMap::COL_NAME, static::COL_BUSINESS_UNIT_OWNER_NAME)
            ->withColumn(sprintf('GROUP_CONCAT(DISTINCT %s)', 'assigneeCompanyBusinessUnits.name'), static::COL_ASSIGNEE_COMPANY_BUSINESS_UNIT_NAMES);

        $merchantRelationRequestQuery = $this->applyFilters($merchantRelationRequestQuery);

        return $merchantRelationRequestQuery;
    }

    /**
     * @param \Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestQuery $merchantRelationRequestQuery
     *
     * @return \Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestQuery
     */
    protected function applyFilters(
        SpyMerchantRelationRequestQuery $merchantRelationRequestQuery
    ): SpyMerchantRelationRequestQuery {
        if ($this->merchantRelationRequestConditionsTransfer->getCompanyIds()) {
            $merchantRelationRequestQuery->useCompanyBusinessUnitQuery()
                    ->filterByFkCompany_In($this->merchantRelationRequestConditionsTransfer->getCompanyIds())
                ->endUse();
        }

        if ($this->merchantRelationRequestConditionsTransfer->getMerchantIds()) {
            $merchantRelationRequestQuery->filterByFkMerchant_In(
                $this->merchantRelationRequestConditionsTransfer->getMerchantIds(),
            );
        }

        return $merchantRelationRequestQuery;
    }

    /**
     * @param string $status
     *
     * @return string
     */
    protected function getStatusLabel(string $status): string
    {
        $class = $this->merchantRelationRequestGuiConfig->getStatusClassLabelMapping()[$status] ?? static::STATUS_LABEL_DEFAULT;

        return $this->generateLabel($status, $class);
    }

    /**
     * @param array<string, mixed> $item
     *
     * @return string
     */
    protected function getMerchantNameColumnData(array $item): string
    {
        return sprintf('%s - %s', $item[static::COL_ID_MERCHANT], $item[static::COL_MERCHANT_NAME]);
    }

    /**
     * @param array<string, mixed> $item
     *
     * @return string
     */
    protected function getActionsColumnData(array $item): string
    {
        if ($this->isEditableMerchantRelationRequest($item[SpyMerchantRelationRequestTableMap::COL_STATUS])) {
            return $this->generateEditButton(
                Url::generate(static::URL_MERCHANT_RELATIONSHIP_REQUEST_EDIT, [
                    static::PARAM_ID_MERCHANT_RELATION_REQUEST => $item[SpyMerchantRelationRequestTableMap::COL_ID_MERCHANT_RELATION_REQUEST],
                ]),
                'Edit',
            );
        }

        return $this->generateViewButton(
            Url::generate(static::URL_MERCHANT_RELATIONSHIP_REQUEST_EDIT, [
                static::PARAM_ID_MERCHANT_RELATION_REQUEST => $item[SpyMerchantRelationRequestTableMap::COL_ID_MERCHANT_RELATION_REQUEST],
            ]),
            'View',
        );
    }

    /**
     * @param string $status
     *
     * @return bool
     */
    protected function isEditableMerchantRelationRequest(string $status): bool
    {
        return in_array($status, $this->merchantRelationRequestGuiConfig->getEditableMerchantRelationRequestStatuses(), true);
    }
}
