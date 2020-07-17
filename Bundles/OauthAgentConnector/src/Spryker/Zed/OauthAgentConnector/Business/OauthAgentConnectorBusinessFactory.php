<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthAgentConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\OauthAgentConnector\Business\Installer\AgentOauthScopeInstaller;
use Spryker\Zed\OauthAgentConnector\Business\Installer\AgentOauthScopeInstallerInterface;
use Spryker\Zed\OauthAgentConnector\Business\OauthUserProvider\AgentProvider;
use Spryker\Zed\OauthAgentConnector\Business\OauthUserProvider\AgentProviderInterface;
use Spryker\Zed\OauthAgentConnector\Business\ScopeProvider\ScopeProvider;
use Spryker\Zed\OauthAgentConnector\Business\ScopeProvider\ScopeProviderInterface;
use Spryker\Zed\OauthAgentConnector\Dependency\Facade\OauthAgentConnectorToAgentFacadeInterface;
use Spryker\Zed\OauthAgentConnector\Dependency\Facade\OauthAgentConnectorToOauthFacadeInterface;
use Spryker\Zed\OauthAgentConnector\Dependency\Service\OauthAgentConnectorToUtilEncodingServiceInterface;
use Spryker\Zed\OauthAgentConnector\OauthAgentConnectorConfig;
use Spryker\Zed\OauthAgentConnector\OauthAgentConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\OauthAgentConnector\OauthAgentConnectorConfig getConfig()
 */
class OauthAgentConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\OauthAgentConnector\Business\OauthUserProvider\AgentProviderInterface
     */
    public function createCustomerProvider(): AgentProviderInterface
    {
        return new AgentProvider(
            $this->getCustomerFacade(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\OauthAgentConnector\Business\ScopeProvider\ScopeProviderInterface
     */
    public function createScopeProvider(): ScopeProviderInterface
    {
        return new ScopeProvider($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\OauthAgentConnector\Business\Installer\AgentOauthScopeInstallerInterface
     */
    public function createAgentOauthScopeInstaller(): AgentOauthScopeInstallerInterface
    {
        return new AgentOauthScopeInstaller(
            $this->getOauthFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\OauthAgentConnector\Dependency\Facade\OauthAgentConnectorToAgentFacadeInterface
     */
    public function getCustomerFacade(): OauthAgentConnectorToAgentFacadeInterface
    {
        return $this->getProvidedDependency(OauthAgentConnectorDependencyProvider::FACADE_CUSTOMER);
    }

    /**
     * @return \Spryker\Zed\OauthAgentConnector\Dependency\Facade\OauthAgentConnectorToOauthFacadeInterface
     */
    public function getOauthFacade(): OauthAgentConnectorToOauthFacadeInterface
    {
        return $this->getProvidedDependency(OauthAgentConnectorDependencyProvider::FACADE_OAUTH);
    }

    /**
     * @return \Spryker\Zed\OauthAgentConnector\OauthAgentConnectorConfig
     */
    public function getModuleConfig(): OauthAgentConnectorConfig
    {
        return $this->getConfig();
    }

    /**
     * @return \Spryker\Zed\OauthAgentConnector\Dependency\Service\OauthAgentConnectorToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): OauthAgentConnectorToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(OauthAgentConnectorDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
