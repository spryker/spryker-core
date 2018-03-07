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
    public function queryCompanyUnitAddress()
    {
        //TODO: move create to factory
        //TODO: move joins to separate method named like ...WIthSmth
        return SpyCompanyUnitAddressQuery::create()
            ->innerJoinWithCompany()
            ->innerJoinWithCountry()
            ->innerJoinWithRegion();
    }
}
