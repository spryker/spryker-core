<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PriceProductVolumesRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\PriceProductVolumesRestApi\Dependency\Client\PriceProductVolumesRestApiToPriceProductVolumeClientBridge;

/**
 * @method \Spryker\Glue\PriceProductVolumesRestApi\PriceProductVolumesRestApiConfig getConfig()
 */
class PriceProductVolumesRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_PRICE_PRODUCT_VOLUME = 'CLIENT_PRICE_PRODUCT_VOLUME';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addPriceProductVolumeClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addPriceProductVolumeClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRICE_PRODUCT_VOLUME, function (Container $container) {
            return new PriceProductVolumesRestApiToPriceProductVolumeClientBridge(
                $container->getLocator()->priceProductVolume()->client()
            );
        });

        return $container;
    }
}
