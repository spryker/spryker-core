<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthPermission\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\OauthPermission\Business\Expander\CustomerIdentifierExpander;
use Spryker\Zed\OauthPermission\Business\Expander\CustomerIdentifierExpanderInterface;
use Spryker\Zed\OauthPermission\Dependency\Facade\OauthPermissionToPermissionFacadeInterface;
use Spryker\Zed\OauthPermission\OauthPermissionDependencyProvider;

/**
 * @method \Spryker\Zed\OauthPermission\OauthPermissionConfig getConfig()
 */
class OauthPermissionBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\OauthPermission\Business\Expander\CustomerIdentifierExpanderInterface
     */
    public function createCustomerIdentifierExpander(): CustomerIdentifierExpanderInterface
    {
        return new CustomerIdentifierExpander($this->getPermissionFacade());
    }

    /**
     * @return \Spryker\Zed\OauthPermission\Dependency\Facade\OauthPermissionToPermissionFacadeInterface
     */
    public function getPermissionFacade(): OauthPermissionToPermissionFacadeInterface
    {
        return $this->getProvidedDependency(OauthPermissionDependencyProvider::FACADE_PERMISSION);
    }
}
