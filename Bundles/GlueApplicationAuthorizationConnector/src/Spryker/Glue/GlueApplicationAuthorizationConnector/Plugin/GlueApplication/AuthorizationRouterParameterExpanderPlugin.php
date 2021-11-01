<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplicationAuthorizationConnector\Plugin\GlueApplication;

use Spryker\Glue\GlueApplicationAuthorizationConnectorExtension\Dependency\Plugin\AuthorizationStrategyAwareResourceRoutePluginInterface;
use Spryker\Glue\GlueApplicationAuthorizationConnectorExtension\Dependency\Plugin\DefaultAuthorizationStrategyAwareResourceRoutePluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RouterParameterExpanderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Shared\GlueApplicationAuthorizationConnector\GlueApplicationAuthorizationConnectorConfig;

/**
 * @method \Spryker\Glue\GlueApplicationAuthorizationConnector\GlueApplicationAuthorizationConnectorFactory getFactory()
 */
class AuthorizationRouterParameterExpanderPlugin extends AbstractPlugin implements RouterParameterExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands route authorization сonfigurations, route authorization default configuration.
     *
     * @api
     *
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $resourceRoutePlugin
     * @param array<mixed> $resourceConfiguration
     *
     * @return array<mixed>
     */
    public function expandResourceConfiguration(ResourceRoutePluginInterface $resourceRoutePlugin, array $resourceConfiguration): array
    {
        if ($resourceRoutePlugin instanceof DefaultAuthorizationStrategyAwareResourceRoutePluginInterface) {
            $resourceConfiguration[GlueApplicationAuthorizationConnectorConfig::ATTRIBUTE_ROUTE_AUTHORIZATION_DEFAULT_CONFIGURATION] = $resourceRoutePlugin->getRouteAuthorizationDefaultConfiguration();
        }

        if ($resourceRoutePlugin instanceof AuthorizationStrategyAwareResourceRoutePluginInterface) {
            $resourceConfiguration[GlueApplicationAuthorizationConnectorConfig::ATTRIBUTE_ROUTE_AUTHORIZATION_CONFIGURATIONS] = $resourceRoutePlugin->getRouteAuthorizationConfigurations();
        }

        return $resourceConfiguration;
    }

    /**
     * {@inheritDoc}
     * - Expands route authorization сonfigurations, route authorization default configuration.
     *
     * @api
     *
     * @param array<mixed> $resourceConfiguration
     * @param array<mixed> $routeParams
     *
     * @return array<mixed>
     */
    public function expandRouteParameters(array $resourceConfiguration, array $routeParams): array
    {
        if (isset($resourceConfiguration[GlueApplicationAuthorizationConnectorConfig::ATTRIBUTE_ROUTE_AUTHORIZATION_DEFAULT_CONFIGURATION])) {
            $routeParams[GlueApplicationAuthorizationConnectorConfig::ATTRIBUTE_ROUTE_AUTHORIZATION_DEFAULT_CONFIGURATION] = $resourceConfiguration[GlueApplicationAuthorizationConnectorConfig::ATTRIBUTE_ROUTE_AUTHORIZATION_DEFAULT_CONFIGURATION];
        }

        if (isset($resourceConfiguration[GlueApplicationAuthorizationConnectorConfig::ATTRIBUTE_ROUTE_AUTHORIZATION_CONFIGURATIONS])) {
            $routeParams[GlueApplicationAuthorizationConnectorConfig::ATTRIBUTE_ROUTE_AUTHORIZATION_CONFIGURATIONS] = $resourceConfiguration[GlueApplicationAuthorizationConnectorConfig::ATTRIBUTE_ROUTE_AUTHORIZATION_CONFIGURATIONS];
        }

        return $routeParams;
    }
}
