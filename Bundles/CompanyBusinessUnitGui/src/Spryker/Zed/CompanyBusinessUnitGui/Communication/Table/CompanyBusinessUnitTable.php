<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Table;

use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

/**
 * @method \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit[] runQuery(\Propel\Runtime\ActiveQuery\ModelCriteria $query, TableConfiguration $config, bool $returnRawResults = false)
 */
class CompanyBusinessUnitTable extends AbstractTable
{
    protected const COL_ID_COMPANY_BUSINESS_UNIT = SpyCompanyBusinessUnitTableMap::COL_ID_COMPANY_BUSINESS_UNIT;
    protected const COL_COMPANY_NAME = SpyCompanyTableMap::COL_NAME;
    protected const COL_NAME = SpyCompanyBusinessUnitTableMap::COL_NAME;
    protected const COL_PARENT_NAME = 'parent.name';
    protected const COL_ADDRESS = 'address';
    protected const COL_IBAN = SpyCompanyBusinessUnitTableMap::COL_IBAN;
    protected const COL_BIC = SpyCompanyBusinessUnitTableMap::COL_BIC;
    protected const COL_ACTIONS = 'actions';

    protected const TABLE_PARENT_UNIT = 'parent';
    protected const REQUEST_ID_COMPANY_BUSINESS_UNIT = 'id-company-business-unit';
    protected const URL_COMPANY_BUSINESS_UNIT_EDIT = '/company-business-unit-gui/edit-company-business-unit/index?%s=%d';
    protected const FORMAT_ADDRESS = '%s, %s, %s';

    /**
     * @var \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery
     */
    protected $companyBusinessUnitQuery;

    /**
     * @param \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery $companyBusinessUnitQuery
     */
    public function __construct(SpyCompanyBusinessUnitQuery $companyBusinessUnitQuery)
    {
        $companyBusinessUnitQuery->leftJoinCompany();
        $companyBusinessUnitQuery->leftJoinParentCompanyBusinessUnit(static::TABLE_PARENT_UNIT);
        $this->companyBusinessUnitQuery = $companyBusinessUnitQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COL_ID_COMPANY_BUSINESS_UNIT => 'Id',
            static::COL_COMPANY_NAME => 'Company',
            static::COL_NAME => 'Name',
            static::COL_PARENT_NAME => 'Parent',
            static::COL_ADDRESS => 'Address',
            static::COL_IBAN => 'IBAN',
            static::COL_BIC => 'BIC',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->addRawColumn(static::COL_ADDRESS);
        $config->addRawColumn(static::COL_ACTIONS);

        $config->setSortable([
            static::COL_ID_COMPANY_BUSINESS_UNIT,
            static::COL_COMPANY_NAME,
            static::COL_NAME,
            static::COL_PARENT_NAME,
        ]);

        $config->setSearchable([
            static::COL_NAME,
            static::COL_COMPANY_NAME,
            static::COL_PARENT_NAME,
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
        $queryResults = $this->runQuery($this->companyBusinessUnitQuery, $config, true);
        $results = [];

        foreach ($queryResults as $item) {
            $parent = $item->getParentCompanyBusinessUnit();

            $results[] = [
                static::COL_ID_COMPANY_BUSINESS_UNIT => $item->getIdCompanyBusinessUnit(),
                static::COL_COMPANY_NAME => $item->getCompany()->getName(),
                static::COL_NAME => $item->getName(),
                static::COL_PARENT_NAME => $parent ? $parent->getName() : '',
                static::COL_ADDRESS => $this->formatAddress($item),
                static::COL_IBAN => $item->getIban(),
                static::COL_BIC => $item->getBic(),
                static::COL_ACTIONS => $this->buildLinks($item),
            ];
        }
        unset($queryResults);

        return $results;
    }

    /**
     * @param \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit $spyCompanyBusinessUnit
     *
     * @return string
     */
    protected function formatAddress(SpyCompanyBusinessUnit $spyCompanyBusinessUnit): string
    {
        $result = '';
        $spyCompanyUnitAddress = $spyCompanyBusinessUnit
            ->getSpyCompanyUnitAddressToCompanyBusinessUnitsJoinCompanyUnitAddress();
        if ($spyCompanyUnitAddress->count() > 0) {
            $address = $spyCompanyUnitAddress[0]->getCompanyUnitAddress();
            $result = sprintf(
                static::FORMAT_ADDRESS,
                $address->getCity(),
                $address->getAddress1(),
                $address->getZipCode()
            );
        }

        return $result;
    }

    /**
     * @param \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit $spyCompanyBusinessUnit
     *
     * @return string
     */
    protected function buildLinks(SpyCompanyBusinessUnit $spyCompanyBusinessUnit): string
    {
        $buttons = [];

        $buttons[] = $this->generateEditButton(
            sprintf(
                static::URL_COMPANY_BUSINESS_UNIT_EDIT,
                static::REQUEST_ID_COMPANY_BUSINESS_UNIT,
                $spyCompanyBusinessUnit->getIdCompanyBusinessUnit()
            ),
            'Edit'
        );

        return implode(' ', $buttons);
    }
}
