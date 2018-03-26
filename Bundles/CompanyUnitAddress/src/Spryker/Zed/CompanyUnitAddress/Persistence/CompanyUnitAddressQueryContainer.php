<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Persistence;

use Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressPersistenceFactory getFactory()
 */
class CompanyUnitAddressQueryContainer extends AbstractQueryContainer implements CompanyUnitAddressQueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery
     */
    public function queryCompanyUnitAddress(): SpyCompanyUnitAddressQuery
    {
        return $this->getFactory()
            ->createCompanyUnitAddressQuery();
    }

    /**
     * @api
     *
     * @param int $idCompanyUnitAddress
     *
     * @return \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery
     */
    public function queryCompanyUnitAddressWithCountryById(int $idCompanyUnitAddress): SpyCompanyUnitAddressQuery
    {
        return $this->getFactory()
            ->createCompanyUnitAddressQuery()
            ->joinWithCountry()
            ->filterByIdCompanyUnitAddress($idCompanyUnitAddress);
    }

    /**
     * @api
     *
     * @param int $idCompanyUnitAddress
     *
     * @return \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery
     */
    public function queryCompanyUnitAddressWithCompanyById(int $idCompanyUnitAddress): SpyCompanyUnitAddressQuery
    {
        return $this->getFactory()
            ->createCompanyUnitAddressQuery()
            ->joinWithCompany()
            ->filterByIdCompanyUnitAddress($idCompanyUnitAddress);
    }

    /**
     * @api
     *
     * @param int $idCompanyUnitAddress
     *
     * @return \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery
     */
    public function queryCompanyUnitAddressWithRegionById(int $idCompanyUnitAddress): SpyCompanyUnitAddressQuery
    {
        return $this->getFactory()
            ->createCompanyUnitAddressQuery()
            ->joinWithRegion()
            ->filterByIdCompanyUnitAddress($idCompanyUnitAddress);
    }
}
