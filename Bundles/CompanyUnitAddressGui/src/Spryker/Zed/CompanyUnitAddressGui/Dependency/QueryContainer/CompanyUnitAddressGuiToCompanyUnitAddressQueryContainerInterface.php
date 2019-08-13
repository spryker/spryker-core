<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressGui\Dependency\QueryContainer;

use Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery;

interface CompanyUnitAddressGuiToCompanyUnitAddressQueryContainerInterface
{
    /**
     * @return \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery
     */
    public function queryCompanyUnitAddress();

    /**
     * @return \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery
     */
    public function queryCompanyUnitAddressWithCompanyAndCountry(): SpyCompanyUnitAddressQuery;

    /**
     * @param int $idCompanyUnitAddress
     *
     * @return \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery
     */
    public function queryCompanyUnitAddressWithCountryById(int $idCompanyUnitAddress): SpyCompanyUnitAddressQuery;

    /**
     * @param int $idCompanyUnitAddress
     *
     * @return \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery
     */
    public function queryCompanyUnitAddressWithCompanyById(int $idCompanyUnitAddress): SpyCompanyUnitAddressQuery;

    /**
     * @param int $idCompanyUnitAddress
     *
     * @return \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery
     */
    public function queryCompanyUnitAddressWithRegionById(int $idCompanyUnitAddress): SpyCompanyUnitAddressQuery;
}
