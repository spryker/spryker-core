<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthWarehouseUser\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\OauthWarehouseUser\Business\Checker\WarehouseUserTypeOauthScopeAuthorizationChecker;
use Spryker\Zed\OauthWarehouseUser\Business\Checker\WarehouseUserTypeOauthScopeAuthorizationCheckerInterface;
use Spryker\Zed\OauthWarehouseUser\Business\Provider\WarehouseUserTypeOauthScopeProvider;
use Spryker\Zed\OauthWarehouseUser\Business\Provider\WarehouseUserTypeOauthScopeProviderInterface;
use Spryker\Zed\OauthWarehouseUser\Dependency\Facade\OauthWarehouseUserToUserFacadeInterface;
use Spryker\Zed\OauthWarehouseUser\OauthWarehouseUserDependencyProvider;

/**
 * @method \Spryker\Zed\OauthWarehouseUser\OauthWarehouseUserConfig getConfig()
 * @method \Spryker\Zed\OauthWarehouseUser\Persistence\OauthWarehouseUserEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\OauthWarehouseUser\Persistence\OauthWarehouseUserRepositoryInterface getRepository()
 */
class OauthWarehouseUserBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\OauthWarehouseUser\Business\Provider\WarehouseUserTypeOauthScopeProviderInterface
     */
    public function createWarehouseUserTypeOauthScopeProvider(): WarehouseUserTypeOauthScopeProviderInterface
    {
        return new WarehouseUserTypeOauthScopeProvider(
            $this->getUserFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\OauthWarehouseUser\Business\Checker\WarehouseUserTypeOauthScopeAuthorizationCheckerInterface
     */
    public function createWarehouseUserTypeOauthScopeAuthorizationChecker(): WarehouseUserTypeOauthScopeAuthorizationCheckerInterface
    {
        return new WarehouseUserTypeOauthScopeAuthorizationChecker(
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\OauthWarehouseUser\Dependency\Facade\OauthWarehouseUserToUserFacadeInterface
     */
    public function getUserFacade(): OauthWarehouseUserToUserFacadeInterface
    {
        return $this->getProvidedDependency(OauthWarehouseUserDependencyProvider::FACADE_USER);
    }
}
