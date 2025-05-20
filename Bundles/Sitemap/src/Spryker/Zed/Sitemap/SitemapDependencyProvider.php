<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sitemap;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Sitemap\Dependency\Facade\SitemapToStoreFacadeBridge;
use Spryker\Zed\Sitemap\Dependency\Facade\SitemapToStoreFacadeInterface;
use Spryker\Zed\Sitemap\Dependency\Service\SitemapToFileSystemServiceBridge;
use Spryker\Zed\Sitemap\Dependency\Service\SitemapToFileSystemServiceInterface;

/**
 * @method \Spryker\Zed\Sitemap\SitemapConfig getConfig()
 */
class SitemapDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const SERVICE_FILE_SYSTEM = 'SERVICE_FILE_SYSTEM';

    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const PLUGINS_SITEMAP_GENERATOR_DATA_PROVIDER = 'PLUGINS_SITEMAP_GENERATOR_DATA_PROVIDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addFileSystemService($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addSitemapGeneratorDataProviderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFileSystemService(Container $container): Container
    {
        $container->set(static::SERVICE_FILE_SYSTEM, function (Container $container): SitemapToFileSystemServiceInterface {
            return new SitemapToFileSystemServiceBridge(
                $container->getLocator()->fileSystem()->service(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container): SitemapToStoreFacadeInterface {
            return new SitemapToStoreFacadeBridge(
                $container->getLocator()->store()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSitemapGeneratorDataProviderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SITEMAP_GENERATOR_DATA_PROVIDER, function (): array {
            return $this->getSitemapGeneratorDataProviderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\SitemapExtension\Dependency\Plugin\SitemapGeneratorDataProviderPluginInterface>
     */
    protected function getSitemapGeneratorDataProviderPlugins(): array
    {
        return [];
    }
}
