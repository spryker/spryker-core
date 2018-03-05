<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Business;

use Spryker\Zed\CompanyBusinessUnit\Business\Model\CompanyBusinessUnit;
use Spryker\Zed\CompanyBusinessUnit\Business\Model\CompanyBusinessUnitInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CompanyBusinessUnit\CompanyBusinessUnitConfig getConfig()
 */
class CompanyBusinessUnitBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Business\Model\CompanyBusinessUnitInterface
     */
    public function createCompanyBusinessUnit(): CompanyBusinessUnitInterface
    {
        return new CompanyBusinessUnit(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }
}
