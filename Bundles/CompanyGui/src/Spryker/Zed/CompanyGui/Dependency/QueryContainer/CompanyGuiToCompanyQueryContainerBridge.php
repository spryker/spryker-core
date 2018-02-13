<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyGui\Dependency\QueryContainer;

use Orm\Zed\Company\Persistence\SpyCompanyQuery;

class CompanyGuiToCompanyQueryContainerBridge implements CompanyGuiToCompanyQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\Company\Persistence\CompanyQueryContainerInterface
     */
    protected $companyQueryContainer;

    /**
     * @param \Spryker\Zed\Company\Persistence\CompanyQueryContainerInterface $companyQueryContainer
     */
    public function __construct($companyQueryContainer)
    {
        $this->companyQueryContainer = $companyQueryContainer;
    }

    /**
     * @return \Orm\Zed\Company\Persistence\SpyCompanyQuery
     */
    public function queryCompany(): SpyCompanyQuery
    {
        return $this->companyQueryContainer->queryCompany();
    }
}
