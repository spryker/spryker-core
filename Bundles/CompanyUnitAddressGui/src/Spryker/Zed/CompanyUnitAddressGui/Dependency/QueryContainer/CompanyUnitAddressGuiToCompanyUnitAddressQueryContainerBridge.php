<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressGui\Dependency\QueryContainer;

use Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery;

class CompanyUnitAddressGuiToCompanyUnitAddressQueryContainerBridge implements CompanyUnitAddressGuiToCompanyUnitAddressQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressQueryContainerInterface
     */
    protected $companyUnitAddressQueryContainer;

    /**
     * @param \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressQueryContainerInterface $companyUnitAddressQueryContainer
     */
    public function __construct($companyUnitAddressQueryContainer)
    {
        $this->companyUnitAddressQueryContainer = $companyUnitAddressQueryContainer;
    }

    /**
     * @return \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery
     */
    public function queryCompanyUnitAddress()
    {
        return $this->companyUnitAddressQueryContainer->queryCompanyUnitAddress();
    }

    /**
     * @param int $idCompanyUnitAddress
     *
     * @return \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery
     */
    public function queryCompanyUnitAddressWithCountryById(int $idCompanyUnitAddress): SpyCompanyUnitAddressQuery
    {
        return $this->companyUnitAddressQueryContainer
            ->queryCompanyUnitAddressWithCountryById($idCompanyUnitAddress);
    }

    /**
     * @param int $idCompanyUnitAddress
     *
     * @return \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery
     */
    public function queryCompanyUnitAddressWithCompanyById(int $idCompanyUnitAddress): SpyCompanyUnitAddressQuery
    {
        return $this->companyUnitAddressQueryContainer
            ->queryCompanyUnitAddressWithCompanyById($idCompanyUnitAddress);
    }

    /**
     * @param int $idCompanyUnitAddress
     *
     * @return \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery
     */
    public function queryCompanyUnitAddressWithRegionById(int $idCompanyUnitAddress): SpyCompanyUnitAddressQuery
    {
        return $this->companyUnitAddressQueryContainer
            ->queryCompanyUnitAddressWithRegionById($idCompanyUnitAddress);
    }
}
