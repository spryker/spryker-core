<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthUserConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\OauthUserConnector\Business\Installer\OauthUserScopeInstaller;
use Spryker\Zed\OauthUserConnector\Business\Installer\OauthUserScopeInstallerInterface;
use Spryker\Zed\OauthUserConnector\Business\Provider\ScopeProvider;
use Spryker\Zed\OauthUserConnector\Business\Provider\ScopeProviderInterface;
use Spryker\Zed\OauthUserConnector\Business\Provider\UserProvider;
use Spryker\Zed\OauthUserConnector\Business\Provider\UserProviderInterface;
use Spryker\Zed\OauthUserConnector\Dependency\Facade\OauthUserConnectorToOauthFacadeInterface;
use Spryker\Zed\OauthUserConnector\Dependency\Facade\OauthUserConnectorToUserFacadeInterface;
use Spryker\Zed\OauthUserConnector\Dependency\Service\OauthUserConnectorToUtilEncodingServiceInterface;
use Spryker\Zed\OauthUserConnector\OauthUserConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\OauthUserConnector\OauthUserConnectorConfig getConfig()
 */
class OauthUserConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\OauthUserConnector\Business\Provider\UserProviderInterface
     */
    public function createUserProvider(): UserProviderInterface
    {
        return new UserProvider(
            $this->getUserFacade(),
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \Spryker\Zed\OauthUserConnector\Business\Provider\ScopeProviderInterface
     */
    public function createScopeProvider(): ScopeProviderInterface
    {
        return new ScopeProvider($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\OauthUserConnector\Business\Installer\OauthUserScopeInstallerInterface
     */
    public function createOauthUserScopeInstaller(): OauthUserScopeInstallerInterface
    {
        return new OauthUserScopeInstaller(
            $this->getOauthFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\OauthUserConnector\Dependency\Facade\OauthUserConnectorToUserFacadeInterface
     */
    public function getUserFacade(): OauthUserConnectorToUserFacadeInterface
    {
        return $this->getProvidedDependency(OauthUserConnectorDependencyProvider::FACADE_USER);
    }

    /**
     * @return \Spryker\Zed\OauthUserConnector\Dependency\Facade\OauthUserConnectorToOauthFacadeInterface
     */
    public function getOauthFacade(): OauthUserConnectorToOauthFacadeInterface
    {
        return $this->getProvidedDependency(OauthUserConnectorDependencyProvider::FACADE_OAUTH);
    }

    /**
     * @return \Spryker\Zed\OauthUserConnector\Dependency\Service\OauthUserConnectorToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): OauthUserConnectorToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(OauthUserConnectorDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
