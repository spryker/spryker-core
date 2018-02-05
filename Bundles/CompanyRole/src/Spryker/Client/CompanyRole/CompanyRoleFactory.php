<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyRole;

use Spryker\Client\CompanyRole\Zed\CompanyRoleStub;
use Spryker\Client\CompanyRole\Zed\CompanyRoleStubInterface;
use Spryker\Client\CompanyRole\Dependency\Client\CompanyRoleToCustomerClientInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CompanyRoleFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CompanyRole\Zed\CompanyRoleStubInterface
     */
    public function createZedCompanyRoleStub(): CompanyRoleStubInterface
    {
        return new CompanyRoleStub($this->getProvidedDependency(CompanyRoleDependencyProvider::SERVICE_ZED));
    }

    /**
     * @return CompanyRoleToCustomerClientInterface
     */
    public function getCustomerClient()
    {
        return $this->getProvidedDependency(CompanyRoleDependencyProvider::CLIENT_CUSTOMER);
    }
}
