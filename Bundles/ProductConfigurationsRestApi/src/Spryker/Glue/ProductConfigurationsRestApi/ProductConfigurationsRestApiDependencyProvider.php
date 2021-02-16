<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductConfigurationsRestApi\Dependency\Client\ProductConfigurationsRestApiToProductConfigurationStorageClientBridge;

/**
 * @method \Spryker\Glue\ProductConfigurationsRestApi\ProductConfigurationsRestApiConfig getConfig()
 */
class ProductConfigurationsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_CONFIGURATION_STORAGE = 'CLIENT_PRODUCT_CONFIGURATION_STORAGE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addProductConfigurationStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function addProductConfigurationStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_CONFIGURATION_STORAGE, function (Container $container) {
            return new ProductConfigurationsRestApiToProductConfigurationStorageClientBridge(
                $container->getLocator()->productConfigurationStorage()->client()
            );
        });

        return $container;
    }
}
