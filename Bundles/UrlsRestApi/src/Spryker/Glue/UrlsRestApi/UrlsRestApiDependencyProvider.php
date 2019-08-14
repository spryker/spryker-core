<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UrlsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\UrlsRestApi\Dependency\Client\UrlsRestApiToUrlStorageClientBridge;

/**
 * @method \Spryker\Glue\UrlsRestApi\UrlsRestApiConfig getConfig()
 */
class UrlsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_URL_STORAGE = 'CLIENT_URL_STORAGE';

    public const PLUGINS_RESOURCE_IDENTIFIER_PROVIDER = 'PLUGINS_RESOURCE_IDENTIFIER_PROVIDER';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addUrlStorageClient($container);
        $container = $this->addResourceIdentifierProviderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addUrlStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_URL_STORAGE, function (Container $container) {
            return new UrlsRestApiToUrlStorageClientBridge(
                $container->getLocator()->urlStorage()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addResourceIdentifierProviderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_RESOURCE_IDENTIFIER_PROVIDER, function () {
            return $this->getResourceIdentifierProviderPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Glue\UrlsRestApiExtension\Dependency\Plugin\ResourceIdentifierProviderPluginInterface[]
     */
    protected function getResourceIdentifierProviderPlugins(): array
    {
        return [];
    }
}
