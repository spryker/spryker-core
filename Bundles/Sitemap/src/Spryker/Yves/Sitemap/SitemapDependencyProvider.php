<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Sitemap;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Sitemap\Dependency\Client\SitemapToStoreClientBridge;
use Spryker\Yves\Sitemap\Dependency\Client\SitemapToStoreClientInterface;
use Spryker\Yves\Sitemap\Dependency\Service\SitemapToFileSystemServiceBridge;
use Spryker\Yves\Sitemap\Dependency\Service\SitemapToFileSystemServiceInterface;
use Spryker\Yves\Sitemap\Dependency\Service\SitemapToFlysystemServiceBridge;
use Spryker\Yves\Sitemap\Dependency\Service\SitemapToFlysystemServiceInterface;

/**
 * @method \Spryker\Yves\Sitemap\SitemapConfig getConfig()
 */
class SitemapDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @var string
     */
    public const SERVICE_FILE_SYSTEM = 'SERVICE_FILE_SYSTEM';

    /**
     * @var string
     */
    public const SERVICE_FLYSYSTEM = 'SERVICE_FLYSYSTEM';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addStoreClient($container);
        $container = $this->addFileSystemService($container);
        $container = $this->addFlysystemService($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container): SitemapToStoreClientInterface {
            return new SitemapToStoreClientBridge($container->getLocator()->store()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
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
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addFlysystemService(Container $container): Container
    {
        $container->set(static::SERVICE_FLYSYSTEM, function (Container $container): SitemapToFlysystemServiceInterface {
            return new SitemapToFlysystemServiceBridge(
                $container->getLocator()->flysystem()->service(),
            );
        });

        return $container;
    }
}
