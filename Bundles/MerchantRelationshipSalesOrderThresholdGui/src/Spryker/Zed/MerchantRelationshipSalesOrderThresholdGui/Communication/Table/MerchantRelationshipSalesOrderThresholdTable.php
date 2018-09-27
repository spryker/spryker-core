<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Table;

use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipTableMap;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery;
use Orm\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\SpyMerchantRelationshipSalesOrderThresholdQuery;
use Orm\Zed\SalesOrderThreshold\Persistence\Map\SpySalesOrderThresholdTypeTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class MerchantRelationshipSalesOrderThresholdTable extends AbstractTable
{
    protected const REQUEST_ID_MERCHANT_RELATIONSHIP = 'id-merchant-relationship';
    protected const URL_MERCHANT_RELATIONSHIP_EDIT = '/merchant-relationship-sales-order-threshold-gui/edit';

    protected const COL_ID_MERCHANT_RELATIONSHIP = SpyMerchantRelationshipTableMap::COL_ID_MERCHANT_RELATIONSHIP;

    protected const COL_COMPANY_NAME = 'company_name';
    protected const COL_MERCHANT_RELATIONSHIP_NAME = 'merchant_relationship_name';
    protected const COL_BUSINESS_UNIT_NAME = 'business_unit_name';
    protected const COL_THRESHOLDS = 'merchant_relationship_thresholds';
    protected const COL_THRESHOLD_GROUP = 'threshold_group';
    protected const COL_ACTIONS = 'actions';

    /**
     * @var \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery
     */
    protected $propelMerchantRelationshipQuery;

    /**
     * @var \Orm\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\SpyMerchantRelationshipSalesOrderThresholdQuery
     */
    protected $propelMerchantRelationshipSalesOrderThresholdQuery;

    /**
     * @param \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery $propelMerchantRelationshipQuery
     * @param \Orm\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\SpyMerchantRelationshipSalesOrderThresholdQuery $propelMerchantRelationshipSalesOrderThresholdQuery
     */
    public function __construct(
        SpyMerchantRelationshipQuery $propelMerchantRelationshipQuery,
        SpyMerchantRelationshipSalesOrderThresholdQuery $propelMerchantRelationshipSalesOrderThresholdQuery
    ) {
        $this->propelMerchantRelationshipQuery = $propelMerchantRelationshipQuery;
        $this->propelMerchantRelationshipSalesOrderThresholdQuery = $propelMerchantRelationshipSalesOrderThresholdQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config = $this->setHeader($config);

        $config->addRawColumn(static::COL_THRESHOLDS);
        $config->addRawColumn(static::COL_ACTIONS);

        $config->setSortable([
            static::COL_ID_MERCHANT_RELATIONSHIP,
        ]);

        $config->setSearchable([
            SpyCompanyTableMap::COL_NAME,
            SpyMerchantTableMap::COL_NAME,
            SpyCompanyBusinessUnitTableMap::COL_NAME,
            SpyMerchantRelationshipTableMap::COL_MERCHANT_RELATIONSHIP_KEY,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $query = $this->prepareQuery();
        $queryResults = $this->runQuery($query, $config);
        $results = [];

        foreach ($queryResults as $item) {
            $results[] = $this->prepareRowData($item);
        }
        unset($queryResults);

        return $results;
    }

    /**
     * @uses MerchantRelationship
     * @uses CompanyBusinessUnit
     * @uses Merchant
     * @uses Company
     *
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery
     */
    protected function prepareQuery(): SpyMerchantRelationshipQuery
    {
        $query = $this->propelMerchantRelationshipQuery
            ->joinWithCompanyBusinessUnit()
            ->joinWithMerchant()
            ->joinWith('CompanyBusinessUnit.Company')
            ->withColumn(SpyCompanyBusinessUnitTableMap::COL_NAME, static::COL_BUSINESS_UNIT_NAME)
            ->withColumn(SpyCompanyTableMap::COL_NAME, static::COL_COMPANY_NAME)
            ->withColumn("CONCAT(" . SpyMerchantTableMap::COL_NAME . ", ' ', " . SpyMerchantRelationshipTableMap::COL_MERCHANT_RELATIONSHIP_KEY . ")", static::COL_MERCHANT_RELATIONSHIP_NAME);

        return $query;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setHeader(TableConfiguration $config): TableConfiguration
    {
        $baseData = [
            static::COL_ID_MERCHANT_RELATIONSHIP => 'Id',
            static::COL_COMPANY_NAME => 'Company name',
            static::COL_BUSINESS_UNIT_NAME => 'BU name',
            static::COL_MERCHANT_RELATIONSHIP_NAME => 'Merchant relationship name',
            static::COL_THRESHOLDS => 'Thresholds',
            static::COL_ACTIONS => 'Actions',
        ];

        $config->setHeader($baseData);

        return $config;
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function prepareRowData(array $item): array
    {
        $baseData = [
            static::COL_ID_MERCHANT_RELATIONSHIP => $item[static::COL_ID_MERCHANT_RELATIONSHIP],
            static::COL_COMPANY_NAME => $item[static::COL_COMPANY_NAME],
            static::COL_BUSINESS_UNIT_NAME => $item[static::COL_BUSINESS_UNIT_NAME],
            static::COL_MERCHANT_RELATIONSHIP_NAME => $item[static::COL_MERCHANT_RELATIONSHIP_NAME],
            static::COL_THRESHOLDS => $this->prepareMerchantRelationshipThresholds($item[static::COL_ID_MERCHANT_RELATIONSHIP]),
            static::COL_ACTIONS => $this->buildLinks($item),
        ];

        return $baseData;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function buildLinks(array $item): string
    {
        $buttons = [];

        $urlParams = [static::REQUEST_ID_MERCHANT_RELATIONSHIP => $item[static::COL_ID_MERCHANT_RELATIONSHIP]];
        $buttons[] = $this->generateEditButton(
            Url::generate(static::URL_MERCHANT_RELATIONSHIP_EDIT, $urlParams),
            'Edit'
        );

        return implode(' ', $buttons);
    }

    /**
     * @uses SalesOrderThreshold
     * @uses MerchantRelationshipSalesOrderThreshold
     *
     * @param int $idMerchantRelationship
     *
     * @return string
     */
    protected function prepareMerchantRelationshipThresholds(int $idMerchantRelationship): string
    {
        $query = clone $this->propelMerchantRelationshipSalesOrderThresholdQuery;
        $thresholds = $query
            ->joinWithSalesOrderThresholdType()
            ->filterByFkMerchantRelationship($idMerchantRelationship)
            ->withColumn(SpySalesOrderThresholdTypeTableMap::COL_THRESHOLD_GROUP, static::COL_THRESHOLD_GROUP)
            ->find()
            ->toArray();

        if (empty($thresholds)) {
            return '';
        }

        $resultThresholds = [];
        foreach ($thresholds as $threshold) {
            $resultThresholds[$threshold[static::COL_THRESHOLD_GROUP]] = "<span class='label label-info'>" . $threshold[static::COL_THRESHOLD_GROUP] . "</span>";
        }

        return implode(' ', $resultThresholds);
    }
}
