<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Expander;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\CustomRoutesContextTransfer;
use Symfony\Component\Routing\RouteCollection;

class CustomRoutesContextExpander implements ContextExpanderInterface
{
    /**
     * @var string
     */
    protected const CONTROLLER = '_controller';

    /**
     * @var string
     */
    protected const RESOURCE_NAME = '_resourceName';

    /**
     * @var string
     */
    protected const METHOD = '_method';

    /**
     * @var array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface>
     */
    protected $routeProviderPlugins = [];

    /**
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface> $routeProviderPlugins
     */
    public function __construct(array $routeProviderPlugins)
    {
        $this->routeProviderPlugins = $routeProviderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     *
     * @return \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer
     */
    public function expand(ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer): ApiApplicationSchemaContextTransfer
    {
        $routeCollection = $this->getRouteCollection();

        if ($routeCollection->count() === 0) {
            return $apiApplicationSchemaContextTransfer;
        }

        foreach ($routeCollection->all() as $route) {
            $customRoutesContextTransfer = new CustomRoutesContextTransfer();
            $customRoutesContextTransfer->setPath($route->getPath());

            if (!isset($route->getDefaults()[static::CONTROLLER])) {
                $route->addDefaults($this->getControllerInfo(
                    $apiApplicationSchemaContextTransfer,
                    $route->getDefaults(),
                ));
            }

            $customRoutesContextTransfer->setDefaults($route->getDefaults());
            $apiApplicationSchemaContextTransfer->addCustomRoutesContext($customRoutesContextTransfer);
        }

        return $apiApplicationSchemaContextTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     * @param array<string, string> $routeDefaults
     *
     * @return array<string, array<string|null>>
     */
    protected function getControllerInfo(
        ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer,
        array $routeDefaults
    ): array {
        $result = [];
        /** @var \Generated\Shared\Transfer\ResourceContextTransfer $resourceContext */
        foreach ($apiApplicationSchemaContextTransfer->getResourceContexts()->getArrayCopy() as $resourceContext) {
            if ($resourceContext->getResourceType() !== $routeDefaults[static::RESOURCE_NAME]) {
                continue;
            }
            $resourceMethodConfigurationTransfer = $resourceContext->getDeclaredMethodsOrFail()[$routeDefaults[static::METHOD]];
            $result = [
                static::CONTROLLER => [
                    $resourceMethodConfigurationTransfer->getController() ?? $resourceContext->getController(),
                    $resourceMethodConfigurationTransfer->getAction() ?? sprintf('%sAction', $routeDefaults[static::METHOD]),
                ],
            ];
        }

        return $result;
    }

    /**
     * @return \Symfony\Component\Routing\RouteCollection
     */
    protected function getRouteCollection(): RouteCollection
    {
        $routeCollection = new RouteCollection();
        foreach ($this->routeProviderPlugins as $routeProviderPlugin) {
            $routeCollection = $routeProviderPlugin->addRoutes($routeCollection);
        }

        return $routeCollection;
    }
}
