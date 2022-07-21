<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Cache;

use Generated\Shared\Transfer\ApiControllerConfigurationTransfer;
use ReflectionMethod;
use Symfony\Component\Routing\RouteCollection;

class ControllerCacheCollector implements ControllerCacheCollectorInterface
{
    /**
     * @uses \Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication\ApplicationIdentifierRequestBuilderPlugin::GLUE_BACKEND_API_APPLICATION
     *
     * @var string
     */
    protected const GLUE_BACKEND_API_APPLICATION = 'GLUE_BACKEND_API_APPLICATION';

    /**
     * @var string
     */
    protected const RESOURCE_NAME = '_resourceName';

    /**
     * @var string
     */
    protected const ACTION = 'action';

    /**
     * @var array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface>
     */
    protected $resources = [];

    /**
     * @var array<string, \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface>
     */
    protected $resourcesData = [];

    /**
     * @var array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface>
     */
    protected array $routeProviderPlugins;

    /**
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface> $resources
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface> $routeProviderPlugins
     */
    public function __construct(array $resources, array $routeProviderPlugins)
    {
        $this->resources = $resources;
        $this->routeProviderPlugins = $routeProviderPlugins;
    }

    /**
     * @return array<string, array<string, \Generated\Shared\Transfer\ApiControllerConfigurationTransfer>>
     */
    public function collect(): array
    {
        $apiControllerConfigurationTransfersData = $this->collectResources();
        $apiControllerConfigurationTransfersData = $this->collectCustomRoutes($apiControllerConfigurationTransfersData);

        return $apiControllerConfigurationTransfersData;
    }

    /**
     * @return array<string, array<string, \Generated\Shared\Transfer\ApiControllerConfigurationTransfer>>
     */
    protected function collectResources(): array
    {
        $apiControllerConfigurationTransfersData = [];

        foreach ($this->resources as $resource) {
            $this->resourcesData[$resource->getType()] = $resource;

            foreach ($resource->getDeclaredMethods()->toArrayRecursiveCamelCased() as $method => $configuration) {
                if ($configuration === null) {
                    continue;
                }

                $apiControllerConfigurationTransfersData = $this->mapApiControllerConfigurationTransfer(
                    $resource->getController(),
                    $configuration[static::ACTION] ?? $method . 'Action',
                    $resource->getType(),
                    $apiControllerConfigurationTransfersData,
                );
            }
        }

        return $apiControllerConfigurationTransfersData;
    }

    /**
     * @param array<string, array<string, \Generated\Shared\Transfer\ApiControllerConfigurationTransfer>> $apiControllerConfigurationTransfersData
     *
     * @return array<string, array<string, \Generated\Shared\Transfer\ApiControllerConfigurationTransfer>>
     */
    protected function collectCustomRoutes(array $apiControllerConfigurationTransfersData): array
    {
        $routeCollection = new RouteCollection();
        foreach ($this->routeProviderPlugins as $routeProviderPlugin) {
            $routeCollection = $routeProviderPlugin->addRoutes($routeCollection);
        }

        foreach ($routeCollection as $pathName => $route) {
            $routeDefaults = $route->getDefaults();

            if (isset($routeDefaults['_controller'])) {
                $apiControllerConfigurationTransfersData = $this->mapApiControllerConfigurationTransfer(
                    $routeDefaults['_controller'][0],
                    $routeDefaults['_controller'][1],
                    $pathName,
                    $apiControllerConfigurationTransfersData,
                );

                continue;
            }

            $apiControllerConfigurationTransfersData = $this->mapApiControllerConfigurationTransfer(
                $this->resourcesData[$routeDefaults[static::RESOURCE_NAME]]->getController(),
                $routeDefaults['_method'] . 'Action',
                $routeDefaults[static::RESOURCE_NAME],
                $apiControllerConfigurationTransfersData,
            );
        }

        return $apiControllerConfigurationTransfersData;
    }

    /**
     * @param string $controller
     * @param string $method
     * @param string $path
     * @param array<string, array<string, \Generated\Shared\Transfer\ApiControllerConfigurationTransfer>> $apiControllerConfigurationTransfersData
     *
     * @return array<string, array<string, \Generated\Shared\Transfer\ApiControllerConfigurationTransfer>>
     */
    protected function mapApiControllerConfigurationTransfer(
        string $controller,
        string $method,
        string $path,
        array $apiControllerConfigurationTransfersData
    ): array {
        if (!method_exists($controller, $method)) {
            return $apiControllerConfigurationTransfersData;
        }

        $reflectionMethod = new ReflectionMethod($controller, $method);
        $apiControllerConfigurationTransfer = new ApiControllerConfigurationTransfer();
        $apiControllerConfigurationTransfer->setPath($path);
        $apiControllerConfigurationTransfer->setController($controller);
        $apiControllerConfigurationTransfer->setMethod($method);
        $apiControllerConfigurationTransfer->setApiApplication(static::GLUE_BACKEND_API_APPLICATION);

        $parameters = array_map(function ($parameter) {
            /**
             * @var \ReflectionNamedType $reflectionNamedType
             */
            $reflectionNamedType = $parameter->getType();

            return $reflectionNamedType->getName();
        }, $reflectionMethod->getParameters());
        $apiControllerConfigurationTransfer->setParameters($parameters);

        $apiControllerConfigurationTransfersData[$apiControllerConfigurationTransfer->getApiApplication()][$this->generateCacheKey($apiControllerConfigurationTransfer)] = $apiControllerConfigurationTransfer;

        return $apiControllerConfigurationTransfersData;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiControllerConfigurationTransfer $apiControllerConfigurationTransfer
     *
     * @return string
     */
    protected function generateCacheKey(ApiControllerConfigurationTransfer $apiControllerConfigurationTransfer): string
    {
        return sprintf(
            '%s:%s:%s',
            $apiControllerConfigurationTransfer->getController(),
            $apiControllerConfigurationTransfer->getPath(),
            $apiControllerConfigurationTransfer->getMethod(),
        );
    }
}
