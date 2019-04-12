<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthPermission\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\OauthPermission\Dependency\Facade\OauthPermissionToPermissionFacadeInterface;
use Spryker\Zed\OauthPermission\OauthPermissionDependencyProvider;

/**
 * @method \Spryker\Zed\OauthPermission\OauthPermissionConfig getConfig()
 * @method \Spryker\Zed\OauthPermission\Business\OauthPermissionFacadeInterface getFacade()
 */
class OauthPermissionCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\OauthPermission\Dependency\Facade\OauthPermissionToPermissionFacadeInterface
     */
    public function getPermissionFacade(): OauthPermissionToPermissionFacadeInterface
    {
        return $this->getProvidedDependency(OauthPermissionDependencyProvider::FACADE_PERMISSION);
    }
}
