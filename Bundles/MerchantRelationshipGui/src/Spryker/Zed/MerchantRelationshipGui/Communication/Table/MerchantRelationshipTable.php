<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipGui\Communication\Table;

use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipTableMap;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class MerchantRelationshipTable extends AbstractTable
{
    protected const TABLE_IDENTIFIER = 'merchant-relationship-table';

    /**
     * @var \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery
     */
    protected $merchantRelationshipQuery;

    /**
     * @var int|null
     */
    protected $idCompany;

    /**
     * @param \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery $merchantRelationshipQuery
     * @param int|null $idCompany
     */
    public function __construct(
        SpyMerchantRelationshipQuery $merchantRelationshipQuery,
        ?int $idCompany = null
    ) {
        $this->merchantRelationshipQuery = $merchantRelationshipQuery;
        $this->idCompany = $idCompany;
        $this->setTableIdentifier(static::TABLE_IDENTIFIER);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $url = Url::generate('table', ['id-company' => $this->idCompany]);

        $config->setUrl($url->build());
        $config = $this->setHeader($config);
        $config = $this->setHeader($config);

        $config->setSortable([
            MerchantRelationshipTableConstants::COL_ID_MERCHANT_RELATIONSHIP,
            MerchantRelationshipTableConstants::COL_MERCHANT_NAME,
            MerchantRelationshipTableConstants::COL_BUSINESS_UNIT_OWNER,
        ]);

        $config->setSearchable([
            MerchantRelationshipTableConstants::COL_ID_MERCHANT_RELATIONSHIP,
            SpyMerchantTableMap::COL_NAME,
            SpyCompanyBusinessUnitTableMap::COL_NAME,
        ]);

        $config->addRawColumn(MerchantRelationshipTableConstants::COL_ACTIONS);
        $config->setDefaultSortField(MerchantRelationshipTableConstants::COL_ID_MERCHANT_RELATIONSHIP, TableConfiguration::SORT_DESC);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setHeader(TableConfiguration $config): TableConfiguration
    {
        $baseData = [
            MerchantRelationshipTableConstants::COL_ID_MERCHANT_RELATIONSHIP => 'ID',
            MerchantRelationshipTableConstants::COL_MERCHANT_NAME => 'Merchant Name',
            MerchantRelationshipTableConstants::COL_BUSINESS_UNIT_OWNER => 'Business Unit Owner',
            MerchantRelationshipTableConstants::COL_ASSIGNED_BUSINESS_UNITS => 'Assigned Business Units',
        ];

        $actions = [MerchantRelationshipTableConstants::COL_ACTIONS => 'Actions'];

        $config->setHeader($baseData + $actions);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $queryResults = $this->runQuery($this->getQuery(), $config);
        $results = [];

        foreach ($queryResults as $item) {
            $rowData = [
                MerchantRelationshipTableConstants::COL_ID_MERCHANT_RELATIONSHIP => $item[SpyMerchantRelationshipTableMap::COL_ID_MERCHANT_RELATIONSHIP],
                MerchantRelationshipTableConstants::COL_MERCHANT_NAME => $this->formatMerchantName($item),
                MerchantRelationshipTableConstants::COL_BUSINESS_UNIT_OWNER => $item[MerchantRelationshipTableConstants::COL_BUSINESS_UNIT_OWNER],
                MerchantRelationshipTableConstants::COL_ASSIGNED_BUSINESS_UNITS => $item[MerchantRelationshipTableConstants::COL_ASSIGNED_BUSINESS_UNITS],
                MerchantRelationshipTableConstants::COL_ACTIONS => $this->buildLinks($item),
            ];
            $results[] = $rowData;
        }
        unset($queryResults);

        return $results;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function buildLinks(array $item): string
    {
        $buttons = [];

        $urlParams = [MerchantRelationshipTableConstants::REQUEST_ID_MERCHANT_RELATIONSHIP => $item[MerchantRelationshipTableConstants::COL_ID_MERCHANT_RELATIONSHIP]];
        $buttons[] = $this->generateEditButton(
            Url::generate(MerchantRelationshipTableConstants::URL_MERCHANT_RELATIONSHIP_EDIT, $urlParams),
            'Edit'
        );
        $buttons[] = $this->generateRemoveButton(
            Url::generate(MerchantRelationshipTableConstants::URL_MERCHANT_RELATIONSHIP_DELETE, $urlParams),
            'Delete'
        );

        return implode(' ', $buttons);
    }

    /**
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function getQuery(): ModelCriteria
    {
        $query = $this->merchantRelationshipQuery
            ->groupByIdMerchantRelationship()
            ->innerJoinMerchant()
            ->innerJoinCompanyBusinessUnit()
            ->useSpyMerchantRelationshipToCompanyBusinessUnitQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinCompanyBusinessUnit('assignedBusinessUnits')
                ->withColumn("STRING_AGG( DISTINCT assignedBusinessUnits.name, '; ')", MerchantRelationshipTableConstants::COL_ASSIGNED_BUSINESS_UNITS)
            ->endUse()
            ->withColumn(SpyMerchantTableMap::COL_ID_MERCHANT, MerchantRelationshipTableConstants::COL_MERCHANT_ID)
            ->withColumn(SpyMerchantTableMap::COL_NAME, MerchantRelationshipTableConstants::COL_MERCHANT_NAME)
            ->withColumn(SpyCompanyBusinessUnitTableMap::COL_NAME, MerchantRelationshipTableConstants::COL_BUSINESS_UNIT_OWNER);

        if ($this->idCompany) {
            $query->add(SpyCompanyBusinessUnitTableMap::COL_FK_COMPANY, $this->idCompany, Criteria::EQUAL);
        }

        return $query;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function formatMerchantName(array $item): string
    {
        return sprintf(
            '%d - %s',
            $item[MerchantRelationshipTableConstants::COL_MERCHANT_ID],
            $item[MerchantRelationshipTableConstants::COL_MERCHANT_NAME]
        );
    }
}
