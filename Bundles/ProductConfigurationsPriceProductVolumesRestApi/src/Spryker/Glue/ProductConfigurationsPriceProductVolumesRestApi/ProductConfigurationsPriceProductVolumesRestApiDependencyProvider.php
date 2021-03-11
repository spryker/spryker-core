<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsPriceProductVolumesRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductConfigurationsPriceProductVolumesRestApi\Dependency\Service\ProductConfigurationsPriceProductVolumesRestApiToProductConfigurationServiceBridge;
use Spryker\Glue\ProductConfigurationsPriceProductVolumesRestApi\Dependency\Service\ProductConfigurationsPriceProductVolumesRestApiToUtilEncodingServiceBridge;

/**
 * @method \Spryker\Glue\ProductConfigurationsPriceProductVolumesRestApi\ProductConfigurationsPriceProductVolumesRestApiConfig getConfig()
 */
class ProductConfigurationsPriceProductVolumesRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_PRODUCT_CONFIGURATION = 'SERVICE_PRODUCT_CONFIGURATION';
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addProductConfigurationService($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductConfigurationService(Container $container): Container
    {
        $container->set(static::SERVICE_PRODUCT_CONFIGURATION, function (Container $container) {
            return new ProductConfigurationsPriceProductVolumesRestApiToProductConfigurationServiceBridge(
                $container->getLocator()->productConfiguration()->service()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new ProductConfigurationsPriceProductVolumesRestApiToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        });

        return $container;
    }
}
