<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Business;

use Spryker\Zed\CompanyRole\Business\Model\CompanyRole;
use Spryker\Zed\CompanyRole\Business\Model\CompanyRoleInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanyRole\Persistence\CompanyRoleEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CompanyRole\CompanyRoleConfig getConfig()
 */
class CompanyRoleBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CompanyRole\Business\Model\CompanyRoleInterface
     */
    public function createCompanyRole(): CompanyRoleInterface
    {
        return new CompanyRole(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }
}
