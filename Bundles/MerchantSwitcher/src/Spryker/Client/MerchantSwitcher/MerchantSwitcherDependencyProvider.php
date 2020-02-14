<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantSwitcher;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\MerchantSwitcher\Dependency\Client\MerchantSwitcherToMerchantProductOfferClientBridge;

class MerchantSwitcherDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_MERCHANT_PRODUCT_OFFER = 'CLIENT_MERCHANT_PRODUCT_OFFER';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container = $this->addMerchantProductOfferClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addMerchantProductOfferClient(Container $container): Container
    {
        $container->set(static::CLIENT_MERCHANT_PRODUCT_OFFER, function (Container $container) {
            return new MerchantSwitcherToMerchantProductOfferClientBridge($container->getLocator()->merchantProductOffer()->client());
        });

        return $container;
    }
}
