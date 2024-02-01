<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplicationAuthorizationConnector;

use Spryker\Glue\GlueBackendApiApplicationAuthorizationConnector\ConfigExtractorStrategy\AuthorizationStrategyAwareResourceRoutePluginConfigExtractorStrategy;
use Spryker\Glue\GlueBackendApiApplicationAuthorizationConnector\ConfigExtractorStrategy\ConfigExtractorStrategyInterface;
use Spryker\Glue\GlueBackendApiApplicationAuthorizationConnector\ConfigExtractorStrategy\DefaultAuthorizationStrategyAwareResourceRoutePluginConfigExtractorStrategy;
use Spryker\Glue\GlueBackendApiApplicationAuthorizationConnector\ConfigExtractorStrategy\GenericResourceAuthorizationConfigExtractorStrategy;
use Spryker\Glue\GlueBackendApiApplicationAuthorizationConnector\Dependency\Facade\GlueBackendApiApplicationAuthorizationConnectorToAuthorizationFacadeInterface;
use Spryker\Glue\GlueBackendApiApplicationAuthorizationConnector\Processor\AuthorizationValidator\AuthorizationValidator;
use Spryker\Glue\GlueBackendApiApplicationAuthorizationConnector\Processor\AuthorizationValidator\AuthorizationValidatorInterface;
use Spryker\Glue\Kernel\Backend\AbstractFactory;
use Spryker\Zed\GlueBackendApiApplicationAuthorizationConnector\Business\GlueBackendApiApplicationAuthorizationConnectorFacadeInterface;

/**
 * @method \Spryker\Zed\GlueBackendApiApplicationAuthorizationConnector\Business\GlueBackendApiApplicationAuthorizationConnectorFacadeInterface getFacade()
 */
class GlueBackendApiApplicationAuthorizationConnectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\GlueBackendApiApplicationAuthorizationConnector\Processor\AuthorizationValidator\AuthorizationValidatorInterface
     */
    public function createAuthorizationValidator(): AuthorizationValidatorInterface
    {
        return new AuthorizationValidator(
            $this->getAuthorizationFacade(),
            $this->getConfigExtractorStrategies(),
            $this->getGlueBackendApiApplicationAuthorizationConnectorFacade(),
            $this->getAuthorizationRequestExpanderPlugins(),
            $this->getProtectedRouteAuthorizationConfigProviderPlugins(),
        );
    }

    /**
     * @return \Spryker\Glue\GlueBackendApiApplicationAuthorizationConnector\ConfigExtractorStrategy\ConfigExtractorStrategyInterface
     */
    public function createAuthorizationStrategyAwareResourceRoutePluginConfigExtractorStrategy(): ConfigExtractorStrategyInterface
    {
        return new AuthorizationStrategyAwareResourceRoutePluginConfigExtractorStrategy();
    }

    /**
     * @return \Spryker\Glue\GlueBackendApiApplicationAuthorizationConnector\ConfigExtractorStrategy\ConfigExtractorStrategyInterface
     */
    public function createDefaultAuthorizationStrategyAwareResourceRoutePluginConfigExtractorStrategy(): ConfigExtractorStrategyInterface
    {
        return new DefaultAuthorizationStrategyAwareResourceRoutePluginConfigExtractorStrategy();
    }

    /**
     * @return \Spryker\Glue\GlueBackendApiApplicationAuthorizationConnector\ConfigExtractorStrategy\ConfigExtractorStrategyInterface
     */
    public function createGenericResourceAuthorizationConfigExtractorStrategy(): ConfigExtractorStrategyInterface
    {
        return new GenericResourceAuthorizationConfigExtractorStrategy();
    }

    /**
     * @return array<\Spryker\Glue\GlueBackendApiApplicationAuthorizationConnector\ConfigExtractorStrategy\ConfigExtractorStrategyInterface>
     */
    public function getConfigExtractorStrategies(): array
    {
        return [
            $this->createAuthorizationStrategyAwareResourceRoutePluginConfigExtractorStrategy(),
            $this->createDefaultAuthorizationStrategyAwareResourceRoutePluginConfigExtractorStrategy(),
            $this->createGenericResourceAuthorizationConfigExtractorStrategy(),
        ];
    }

    /**
     * @return \Spryker\Glue\GlueBackendApiApplicationAuthorizationConnector\Dependency\Facade\GlueBackendApiApplicationAuthorizationConnectorToAuthorizationFacadeInterface
     */
    public function getAuthorizationFacade(): GlueBackendApiApplicationAuthorizationConnectorToAuthorizationFacadeInterface
    {
        return $this->getProvidedDependency(GlueBackendApiApplicationAuthorizationConnectorDependencyProvider::FACADE_AUTHORIZATION);
    }

    /**
     * @return \Spryker\Zed\GlueBackendApiApplicationAuthorizationConnector\Business\GlueBackendApiApplicationAuthorizationConnectorFacadeInterface
     */
    public function getGlueBackendApiApplicationAuthorizationConnectorFacade(): GlueBackendApiApplicationAuthorizationConnectorFacadeInterface
    {
        return $this->getProvidedDependency(GlueBackendApiApplicationAuthorizationConnectorDependencyProvider::FACADE_GLUE_BACKEND_API_APPLICATION_AUTHORIZATION_CONNECTOR);
    }

    /**
     * @return list<\Spryker\Glue\GlueBackendApiApplicationAuthorizationConnectorExtension\Dependency\Plugin\AuthorizationRequestExpanderPluginInterface>
     */
    public function getAuthorizationRequestExpanderPlugins(): array
    {
        return $this->getProvidedDependency(GlueBackendApiApplicationAuthorizationConnectorDependencyProvider::PLUGINS_AUTHORIZATION_REQUEST_EXPANDER);
    }

    /**
     * @return list<\Spryker\Glue\GlueBackendApiApplicationAuthorizationConnectorExtension\Dependency\Plugin\ProtectedRouteAuthorizationConfigProviderPluginInterface>
     */
    protected function getProtectedRouteAuthorizationConfigProviderPlugins(): array
    {
        return $this->getProvidedDependency(GlueBackendApiApplicationAuthorizationConnectorDependencyProvider::PLUGINS_PROTECTED_ROUTE_AUTHORIZATION_CONFIG_PROVIDER);
    }
}
