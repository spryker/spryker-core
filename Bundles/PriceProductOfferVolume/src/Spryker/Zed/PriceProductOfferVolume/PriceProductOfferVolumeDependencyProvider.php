<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferVolume;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\PriceProductOfferVolume\PriceProductOfferVolumeConfig getConfig()
 */
class PriceProductOfferVolumeDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_PRICE_PRODUCT_OFFER_VOLUME = 'SERVICE_PRICE_PRODUCT_OFFER_VOLUME';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addPriceProductOfferVolumeService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductOfferVolumeService(Container $container): Container
    {
        $container->set(static::SERVICE_PRICE_PRODUCT_OFFER_VOLUME, function (Container $container) {
            return $container->getLocator()->priceProductOfferVolume()->service();
        });

        return $container;
    }
}
