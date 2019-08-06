<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitStorage\Business;

use Spryker\Zed\CompanyBusinessUnitStorage\Business\CompanyUserStorage\CompanyUserStorageExpander;
use Spryker\Zed\CompanyBusinessUnitStorage\Business\CompanyUserStorage\CompanyUserStorageExpanderInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitStorage\CompanyBusinessUnitStorageConfig getConfig()
 */
class CompanyBusinessUnitStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CompanyBusinessUnitStorage\Business\CompanyUserStorage\CompanyUserStorageExpanderInterface
     */
    public function createCompanyUserStorageExpander(): CompanyUserStorageExpanderInterface
    {
        return new CompanyUserStorageExpander();
    }
}
