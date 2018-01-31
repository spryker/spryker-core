<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Persistence;

use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;

interface CompanyUserQueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    public function queryCompanyUser(): SpyCompanyUserQuery;
}
