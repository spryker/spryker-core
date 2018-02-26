<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyGui\Dependency\QueryContainer;

use Orm\Zed\Company\Persistence\SpyCompanyQuery;

interface CompanyGuiToCompanyQueryContainerInterface
{
    /**
     * @return \Orm\Zed\Company\Persistence\SpyCompanyQuery
     */
    public function queryCompany(): SpyCompanyQuery;
}
