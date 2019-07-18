<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyRole;

use Spryker\Client\CompanyRole\Dependency\Client\CompanyRoleToCustomerClientInterface;
use Spryker\Client\CompanyRole\Dependency\Client\CompanyRoleToPermissionClientInterface;
use Spryker\Client\CompanyRole\Dependency\Client\CompanyRoleToZedRequestClientInterface;
use Spryker\Client\CompanyRole\Permission\CompanyRolePermissionsHandler;
use Spryker\Client\CompanyRole\Permission\CompanyRolePermissionsHandlerInterface;
use Spryker\Client\CompanyRole\Zed\CompanyRoleStub;
use Spryker\Client\CompanyRole\Zed\CompanyRoleStubInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CompanyRoleFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CompanyRole\Zed\CompanyRoleStubInterface
     */
    public function createZedCompanyRoleStub(): CompanyRoleStubInterface
    {
        return new CompanyRoleStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\CompanyRole\Permission\CompanyRolePermissionsHandlerInterface
     */
    public function createCompanyRolePermissionsHandler(): CompanyRolePermissionsHandlerInterface
    {
        return new CompanyRolePermissionsHandler(
            $this->getPermissionClient(),
            $this->createZedCompanyRoleStub()
        );
    }

    /**
     * @return \Spryker\Client\CompanyRole\Dependency\Client\CompanyRoleToCustomerClientInterface
     */
    public function getCustomerClient(): CompanyRoleToCustomerClientInterface
    {
        return $this->getProvidedDependency(CompanyRoleDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Client\CompanyRole\Dependency\Client\CompanyRoleToZedRequestClientInterface
     */
    public function getZedRequestClient(): CompanyRoleToZedRequestClientInterface
    {
        return $this->getProvidedDependency(CompanyRoleDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Spryker\Client\CompanyRole\Dependency\Client\CompanyRoleToPermissionClientInterface
     */
    public function getPermissionClient(): CompanyRoleToPermissionClientInterface
    {
        return $this->getProvidedDependency(CompanyRoleDependencyProvider::CLIENT_PERMISSION);
    }
}
