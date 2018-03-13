<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabel\Persistence;

use Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabelQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelPersistenceFactory getFactory()
 */
class CompanyUnitAddressLabelQueryContainer extends AbstractQueryContainer implements CompanyUnitAddressLabelQueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabelQuery
     */
    public function queryCompanyUnitAddressLabelQuery(): SpyCompanyUnitAddressLabelQuery
    {
        return $this->getFactory()
            ->createCompanyUnitAddressLabelQuery();
    }
}
