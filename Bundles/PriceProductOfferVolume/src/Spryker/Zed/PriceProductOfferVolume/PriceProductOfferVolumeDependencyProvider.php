<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferVolume;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PriceProductOfferVolume\Dependency\External\PriceProductOfferVolumeToValidationAdapter;
use Spryker\Zed\PriceProductOfferVolume\Dependency\Service\PriceProductOfferVolumeToPriceProductVolumeBridge;
use Spryker\Zed\PriceProductOfferVolume\Dependency\Service\PriceProductOfferVolumeToUtilEncodingBridge;

/**
 * @method \Spryker\Zed\PriceProductOfferVolume\PriceProductOfferVolumeConfig getConfig()
 */
class PriceProductOfferVolumeDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const SERVICE_PRICE_PRODUCT_OFFER_VOLUME = 'SERVICE_PRICE_PRODUCT_OFFER_VOLUME';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const SERVICE_PRICE_PRODUCT_VOLUME = 'SERVICE_PRICE_PRODUCT_VOLUME';

    /**
     * @var string
     */
    public const ADAPTER_VALIDATION = 'ADAPTER_VALIDATION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addPriceProductOfferVolumeService($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addValidationAdapter($container);
        $container = $this->addPriceProductVolumeService($container);

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

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new PriceProductOfferVolumeToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addValidationAdapter(Container $container): Container
    {
        $container->set(static::ADAPTER_VALIDATION, function () {
            return new PriceProductOfferVolumeToValidationAdapter();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductVolumeService(Container $container): Container
    {
        $container->set(static::SERVICE_PRICE_PRODUCT_VOLUME, function (Container $container) {
            return new PriceProductOfferVolumeToPriceProductVolumeBridge($container->getLocator()->priceProductVolume()->service());
        });

        return $container;
    }
}
