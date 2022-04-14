<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AppCatalogGui;

use GuzzleHttp\Client as GuzzleHttpClient;
use Spryker\Client\AppCatalogGui\Dependency\External\AppCatalogGuiToGuzzleHttpClientAdapter;
use Spryker\Client\AppCatalogGui\Dependency\Service\AppCatalogGuiToUtilEncodingServiceBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

/**
 * @method \Spryker\Client\AppCatalogGui\AppCatalogGuiConfig getConfig()
 */
class AppCatalogGuiDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_HTTP = 'CLIENT_HTTP';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container = $this->addHttpClient($container);
        $container = $this->addServiceUtilEncoding($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addHttpClient(Container $container): Container
    {
        $container->set(static::CLIENT_HTTP, function () {
            return new AppCatalogGuiToGuzzleHttpClientAdapter(
                new GuzzleHttpClient(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addServiceUtilEncoding(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new AppCatalogGuiToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service(),
            );
        });

        return $container;
    }
}
