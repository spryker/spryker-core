<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Persistence;

use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\CompanyUser\Persistence\CompanyUserPersistenceFactory getFactory()
 */
class CompanyUserQueryContainer extends AbstractQueryContainer implements CompanyUserQueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    public function queryCompanyUser(): SpyCompanyUserQuery
    {
        return $this->getFactory()->createCompanyUserQuery();
    }
}
