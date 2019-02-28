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

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addUrlStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addUrlStorageClient(Container $container): Container
    {
        $container[static::CLIENT_URL_STORAGE] = function (Container $container) {
            return new UrlsRestApiToUrlStorageClientBridge(
                $container->getLocator()->urlStorage()->client()
            );
        };

        return $container;
    }
}
