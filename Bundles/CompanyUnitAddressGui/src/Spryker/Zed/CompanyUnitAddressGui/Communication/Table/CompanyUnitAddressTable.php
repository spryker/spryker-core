<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressGui\Communication\Table;

use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\CompanyUnitAddress\Persistence\Map\SpyCompanyUnitAddressTableMap;
use Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery;
use Orm\Zed\Country\Persistence\Map\SpyCountryTableMap;
use Orm\Zed\Country\Persistence\Map\SpyRegionTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class CompanyUnitAddressTable extends AbstractTable
{
    const COL_ID_COMPANY_UNIT_ADDRESS = SpyCompanyUnitAddressTableMap::COL_ID_COMPANY_UNIT_ADDRESS;
    const COL_ADDRESS1 = SpyCompanyUnitAddressTableMap::COL_ADDRESS1;
    const COL_ADDRESS2 = SpyCompanyUnitAddressTableMap::COL_ADDRESS2;
    const COL_ADDRESS3 = SpyCompanyUnitAddressTableMap::COL_ADDRESS3;
    const COL_CITY = SpyCompanyUnitAddressTableMap::COL_CITY;
    const COL_ZIPCODE = SpyCompanyUnitAddressTableMap::COL_ZIP_CODE;

    const COL_COUNTRY_RELATION = 'Country';
    const COL_REGION_RELATION = 'Region';
    const COL_COMPANY_RELATION = 'Company';

    const COL_ACTIONS = 'Actions';

    const REQUEST_ID_COMPANY_UNIT_ADDRESS = 'id-company-unit-address';

    const URL_COMPANY_UNIT_ADDRESS_EDIT = '/company-unit-address-gui/edit-company-unit-address';

    /**
     * @var \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery
     */
    protected $companyUnitAddressQuery;

    //TODO: inject only query container
    /**
     * @param \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery $companyUnitAddressQuery
     */
    public function __construct(
        SpyCompanyUnitAddressQuery $companyUnitAddressQuery
    ) {
        $this->companyUnitAddressQuery = $companyUnitAddressQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            static::COL_ID_COMPANY_UNIT_ADDRESS => 'Company Unit Address Id',
            static::COL_COUNTRY_RELATION => 'Country',
            static::COL_REGION_RELATION => 'Region',
            static::COL_CITY => 'City',
            static::COL_ZIPCODE => 'Zipcode',
            static::COL_COMPANY_RELATION => 'Company',
            static::COL_ADDRESS1 => 'Address 1',
            static::COL_ADDRESS2 => 'Address 2',
            static::COL_ADDRESS3 => 'Address 3',
            static::COL_ACTIONS => static::COL_ACTIONS,
        ]);

        $config->addRawColumn(static::COL_ACTIONS);
        $config->addRawColumn(static::COL_COUNTRY_RELATION);
        $config->addRawColumn(static::COL_REGION_RELATION);
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
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $queryResults = $this->runQuery($this->companyUnitAddressQuery, $config);
        $results = [];

        foreach ($queryResults as $item) {
            $results[] = [
                static::COL_ID_COMPANY_UNIT_ADDRESS => $item[static::COL_ID_COMPANY_UNIT_ADDRESS],
                static::COL_COUNTRY_RELATION => $this->getCountryName($item[static::COL_COUNTRY_RELATION]),
                static::COL_REGION_RELATION => $this->getRegionName($item[static::COL_REGION_RELATION]),
                static::COL_CITY => $item[static::COL_CITY],
                static::COL_ZIPCODE => $item[static::COL_ZIPCODE],
                static::COL_COMPANY_RELATION => $this->getCompanyName($item[static::COL_COMPANY_RELATION]),
                static::COL_ADDRESS1 => $item[static::COL_ADDRESS1],
                static::COL_ADDRESS2 => $item[static::COL_ADDRESS2],
                static::COL_ADDRESS3 => $item[static::COL_ADDRESS3],
                static::COL_ACTIONS => $this->buildLinks($item),
            ];
        }
        unset($queryResults);

        return $results;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function buildLinks(array $item)
    {
        $buttons = [];

        $buttons[] = $this->generateEditButton(
            Url::generate(static::URL_COMPANY_UNIT_ADDRESS_EDIT, [
                static::REQUEST_ID_COMPANY_UNIT_ADDRESS => $item[static::COL_ID_COMPANY_UNIT_ADDRESS],
            ]),
            'Edit Company Unit Address'
        );

        return implode(' ', $buttons);
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function getCountryName(array $item)
    {
        return $item[SpyCountryTableMap::COL_NAME];
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function getRegionName(array $item)
    {
        return $item[SpyRegionTableMap::COL_NAME];
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function getCompanyName(array $item)
    {
        return $item[SpyCompanyTableMap::COL_NAME];
    }
}
