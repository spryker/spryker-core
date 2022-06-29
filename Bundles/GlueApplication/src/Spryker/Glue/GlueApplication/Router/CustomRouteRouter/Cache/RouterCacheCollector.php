<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Router\CustomRouteRouter\Cache;

use Spryker\Glue\GlueApplication\Router\CustomRouteRouter\Builder\RouterBuilderInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;

class RouterCacheCollector implements RouterCacheCollectorInterface
{
    /**
     * @var array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RoutesProviderPluginInterface>
     */
    protected $routesProviderPlugins;

    /**
     * @var \Spryker\Glue\GlueApplication\Router\CustomRouteRouter\Builder\RouterBuilderInterface
     */
    protected RouterBuilderInterface $routerBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Router\CustomRouteRouter\Builder\RouterBuilderInterface $routerBuilder
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RoutesProviderPluginInterface> $routesProviderPlugins
     */
    public function __construct(
        RouterBuilderInterface $routerBuilder,
        array $routesProviderPlugins
    ) {
        $this->routerBuilder = $routerBuilder;
        $this->routesProviderPlugins = $routesProviderPlugins;
    }

    /**
     * @param array<string> $apiApplications
     *
     * @return void
     */
    public function warmUp(array $apiApplications = []): void
    {
        $filteredApiApplications = $this->getFilteredApiApplications($apiApplications);

        foreach ($this->routesProviderPlugins as $routesProviderPlugin) {
            foreach ($filteredApiApplications as $apiApplication) {
                if ($routesProviderPlugin->getApplicationName() !== $apiApplication) {
                    continue;
                }

                $router = $this->routerBuilder->buildRouter($apiApplication);
                if (!$router instanceof WarmableInterface) {
                    continue;
                }

                $this->clear($routesProviderPlugin->getConfiguration()['options'] ?? []);
                $router->warmUp('');
            }
        }
    }

    /**
     * @param array<mixed> $options
     *
     * @return void
     */
    protected function clear(array $options): void
    {
        $filesystem = new Filesystem();

        if (isset($options['cache_dir']) && is_dir($options['cache_dir'])) {
            $filesystem->remove($options['cache_dir']);
        }
    }

    /**
     * @param array<string> $apiApplications
     *
     * @return array<string>
     */
    protected function getFilteredApiApplications(array $apiApplications): array
    {
        $filteredApiApplications = [];
        foreach ($this->routesProviderPlugins as $routesProviderPlugin) {
            $filteredApiApplications[] = $routesProviderPlugin->getApplicationName();
        }

        if (!count($apiApplications)) {
            return $filteredApiApplications;
        }

        return array_intersect(
            $filteredApiApplications,
            $apiApplications,
        );
    }
}
