<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Collector;

use Generated\Shared\Transfer\OauthScopeFindTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface;
use Spryker\Glue\OauthExtension\Dependency\Plugin\ScopeDefinitionPluginInterface;
use Spryker\Glue\OauthExtension\Dependency\Plugin\ScopeRouteProviderPluginInterface;
use Symfony\Component\Routing\RouteCollection;

class BackendScopeCollector implements BackendScopeCollectorInterface
{
    /**
     * @uses \Spryker\Glue\GlueBackendApiApplication\Plugin\DocumentationGeneratorApi\BackendApiApplicationProviderPlugin::GLUE_BACKEND_API_APPLICATION_NAME
     *
     * @var string
     */
    protected const GLUE_BACKEND_API_APPLICATION_NAME = 'backend';

    /**
     * @var string
     */
    protected const SCOPE_NAME = 'scope';

    /**
     * @var array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface>
     */
    protected $resourcePlugins = [];

    /**
     * @var array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface>
     */
    protected $routeProviderPlugins = [];

    /**
     * @var \Symfony\Component\Routing\RouteCollection
     */
    protected RouteCollection $routeCollection;

    /**
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface> $resourcePlugins
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface> $routeProviderPlugins
     * @param \Symfony\Component\Routing\RouteCollection $routeCollection
     */
    public function __construct(
        array $resourcePlugins,
        array $routeProviderPlugins,
        RouteCollection $routeCollection
    ) {
        $this->resourcePlugins = $resourcePlugins;
        $this->routeProviderPlugins = $routeProviderPlugins;
        $this->routeCollection = $routeCollection;
    }

    /**
     * @return array<\Generated\Shared\Transfer\OauthScopeFindTransfer>
     */
    public function collect(): array
    {
        $scopes = [];
        foreach ($this->resourcePlugins as $resourcePlugin) {
            if ($resourcePlugin instanceof ScopeDefinitionPluginInterface) {
                $scopes = $this->getScopesFromResourcePlugin($resourcePlugin, $scopes);
            }
        }
        foreach ($this->routeProviderPlugins as $routeProviderPlugin) {
            if ($routeProviderPlugin instanceof ScopeRouteProviderPluginInterface) {
                $scopes = $this->getScopesFromRouteProviderPlugin($routeProviderPlugin, $scopes);
            }
        }

        return $scopes;
    }

    /**
     * @param \Spryker\Glue\OauthExtension\Dependency\Plugin\ScopeRouteProviderPluginInterface&\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface $routeProviderPlugin
     * @param array<\Generated\Shared\Transfer\OauthScopeFindTransfer> $scopes
     *
     * @return array<\Generated\Shared\Transfer\OauthScopeFindTransfer>
     */
    protected function getScopesFromRouteProviderPlugin(RouteProviderPluginInterface $routeProviderPlugin, array $scopes): array
    {
        $routes = $routeProviderPlugin->addRoutes($this->routeCollection);
        foreach ($routes->all() as $route) {
            if (isset($route->getDefaults()[static::SCOPE_NAME])) {
                $scopes[] = $this->createOauthScopeFindTransfer($route->getDefaults()[static::SCOPE_NAME]);
            }
        }

        return $scopes;
    }

    /**
     * @param \Spryker\Glue\OauthExtension\Dependency\Plugin\ScopeDefinitionPluginInterface $plugin
     * @param array<\Generated\Shared\Transfer\OauthScopeFindTransfer> $scopes
     *
     * @return array<\Generated\Shared\Transfer\OauthScopeFindTransfer>
     */
    protected function getScopesFromResourcePlugin(ScopeDefinitionPluginInterface $plugin, array $scopes): array
    {
        foreach ($plugin->getScopes() as $scope) {
            $scopes[] = $this->createOauthScopeFindTransfer($scope);
        }

        return $scopes;
    }

    /**
     * @param string $scope
     *
     * @return \Generated\Shared\Transfer\OauthScopeFindTransfer
     */
    protected function createOauthScopeFindTransfer(string $scope): OauthScopeFindTransfer
    {
        return (new OauthScopeFindTransfer())
            ->setApplicationName(static::GLUE_BACKEND_API_APPLICATION_NAME)
            ->setIdentifier($scope);
    }
}
