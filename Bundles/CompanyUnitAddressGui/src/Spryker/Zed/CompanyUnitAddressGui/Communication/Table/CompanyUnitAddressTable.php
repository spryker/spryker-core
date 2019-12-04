<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressGui\Communication\Table;

use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\CompanyUnitAddress\Persistence\Map\SpyCompanyUnitAddressTableMap;
use Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddress;
use Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery;
use Orm\Zed\Country\Persistence\Map\SpyCountryTableMap;
use Propel\Runtime\Map\TableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\CompanyUnitAddressGui\Communication\Table\PluginExecutor\CompanyUnitAddressTablePluginExecutorInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class CompanyUnitAddressTable extends AbstractTable
{
    protected const COL_ID_COMPANY_UNIT_ADDRESS = SpyCompanyUnitAddressTableMap::COL_ID_COMPANY_UNIT_ADDRESS;
    protected const COL_ADDRESS1 = SpyCompanyUnitAddressTableMap::COL_ADDRESS1;
    protected const COL_ADDRESS2 = SpyCompanyUnitAddressTableMap::COL_ADDRESS2;
    protected const COL_ADDRESS3 = SpyCompanyUnitAddressTableMap::COL_ADDRESS3;
    protected const COL_CITY = SpyCompanyUnitAddressTableMap::COL_CITY;
    protected const COL_ZIPCODE = SpyCompanyUnitAddressTableMap::COL_ZIP_CODE;
    protected const COL_COUNTRY_NAME = SpyCountryTableMap::COL_NAME;
    protected const COL_COMPANY_NAME = SpyCompanyTableMap::COL_NAME;

    protected const COL_COUNTRY_RELATION = 'Country';
    protected const COL_COMPANY_RELATION = 'Company';

    protected const COL_ACTIONS = 'Actions';

    protected const REQUEST_ID_COMPANY_UNIT_ADDRESS = 'id-company-unit-address';

    /**
     * @uses \Spryker\Zed\CompanyUnitAddressGui\Communication\Controller\EditCompanyUnitAddressController::indexAction()
     */
    protected const URL_COMPANY_UNIT_ADDRESS_EDIT = '/company-unit-address-gui/edit-company-unit-address';

    /**
     * @var \Spryker\Zed\CompanyUnitAddressGui\Communication\Table\PluginExecutor\CompanyUnitAddressTablePluginExecutorInterface
     */
    protected $companyUnitAddressTablePluginsExecutor;

    /**
     * @var \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery
     */
    protected $companyUnitAddressQuery;

    /**
     * @param \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery $companyUnitAddressQuery
     * @param \Spryker\Zed\CompanyUnitAddressGui\Communication\Table\PluginExecutor\CompanyUnitAddressTablePluginExecutorInterface $companyUnitAddressTablePluginsExecutor
     */
    public function __construct(
        SpyCompanyUnitAddressQuery $companyUnitAddressQuery,
        CompanyUnitAddressTablePluginExecutorInterface $companyUnitAddressTablePluginsExecutor
    ) {
        $this->companyUnitAddressQuery = $companyUnitAddressQuery;
        $this->companyUnitAddressTablePluginsExecutor = $companyUnitAddressTablePluginsExecutor;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config = $this->setHeader($config);

        $config->addRawColumn(static::COL_ACTIONS);
        $config->addRawColumn(static::COL_COUNTRY_RELATION);
        $config->addRawColumn(static::COL_COMPANY_RELATION);

        $config->setSortable([
            static::COL_ID_COMPANY_UNIT_ADDRESS,
            static::COL_CITY,
            static::COL_ZIPCODE,
        ]);

        $config->setDefaultSortField(static::COL_ID_COMPANY_UNIT_ADDRESS, TableConfiguration::SORT_DESC);

        $config->setSearchable([
            static::COL_ID_COMPANY_UNIT_ADDRESS,
            static::COL_CITY,
            static::COL_ZIPCODE,
            static::COL_ADDRESS1,
            static::COL_ADDRESS2,
            static::COL_ADDRESS3,
            static::COL_COUNTRY_NAME,
            static::COL_COMPANY_NAME,
        ]);
        $config = $this->companyUnitAddressTablePluginsExecutor->executeTableConfigExpanderPlugins($config);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $this->companyUnitAddressQuery->leftJoinWithCompany()
            ->leftJoinWithCountry();

        $queryResults = $this->runQuery($this->companyUnitAddressQuery, $config, true);
        $results = [];

        foreach ($queryResults as $item) {
            $results[] = $this->prepareRowData($item);
        }
        unset($queryResults);

        return $results;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setHeader(TableConfiguration $config): TableConfiguration
    {
        $baseData = [
            static::COL_ID_COMPANY_UNIT_ADDRESS => 'Company Unit Address Id',
            static::COL_COUNTRY_RELATION => 'Country',
            static::COL_CITY => 'City',
            static::COL_ZIPCODE => 'Zipcode',
            static::COL_COMPANY_RELATION => 'Company',
            static::COL_ADDRESS1 => 'Address',
            static::COL_ADDRESS2 => 'Number',
            static::COL_ADDRESS3 => 'Addition to address',
        ];

        $externalData = $this->companyUnitAddressTablePluginsExecutor->executeTableHeaderExpanderPlugins();

        $actions = [static::COL_ACTIONS => static::COL_ACTIONS];

        $config->setHeader($baseData + $externalData + $actions);

        return $config;
    }

    /**
     * @param \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddress $companyUnitAddressEntity
     *
     * @return array
     */
    protected function prepareRowData(SpyCompanyUnitAddress $companyUnitAddressEntity): array
    {
        $baseData = [
            static::COL_ID_COMPANY_UNIT_ADDRESS => $companyUnitAddressEntity->getIdCompanyUnitAddress(),
            static::COL_COUNTRY_RELATION => $this->getCountryName($companyUnitAddressEntity),
            static::COL_CITY => $companyUnitAddressEntity->getCity(),
            static::COL_ZIPCODE => $companyUnitAddressEntity->getZipCode(),
            static::COL_COMPANY_RELATION => $this->getCompanyName($companyUnitAddressEntity),
            static::COL_ADDRESS1 => $companyUnitAddressEntity->getAddress1(),
            static::COL_ADDRESS2 => $companyUnitAddressEntity->getAddress2(),
            static::COL_ADDRESS3 => $companyUnitAddressEntity->getAddress3(),
        ];

        $item = $companyUnitAddressEntity->toArray(TableMap::TYPE_COLNAME, false);
        $externalData = $this->companyUnitAddressTablePluginsExecutor->executeTableDataExpanderPlugins($item);

        $actions = [static::COL_ACTIONS => $this->buildLinks($companyUnitAddressEntity)];

        return $baseData + $externalData + $actions;
    }

    /**
     * @param \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddress $companyUnitAddressEntity
     *
     * @return string
     */
    protected function buildLinks(SpyCompanyUnitAddress $companyUnitAddressEntity): string
    {
        $buttons = [];

        $buttons[] = $this->generateEditButton(
            Url::generate(static::URL_COMPANY_UNIT_ADDRESS_EDIT, [
                static::REQUEST_ID_COMPANY_UNIT_ADDRESS => $companyUnitAddressEntity->getIdCompanyUnitAddress(),
            ]),
            'Edit Company Unit Address'
        );

        return implode(' ', $buttons);
    }

    /**
     * @param \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddress $companyUnitAddressEntity
     *
     * @return string
     */
    protected function getCountryName(SpyCompanyUnitAddress $companyUnitAddressEntity): string
    {
        if ($companyUnitAddressEntity->getFkCountry()) {
            return $companyUnitAddressEntity->getCountry()->getName();
        }

        return '';
    }

    /**
     * @param \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddress $companyUnitAddressEntity
     *
     * @return string
     */
    protected function getCompanyName(SpyCompanyUnitAddress $companyUnitAddressEntity): string
    {
        if ($companyUnitAddressEntity->getFkCompany()) {
            return $companyUnitAddressEntity->getCompany()->getName();
        }

        return '';
    }
}
