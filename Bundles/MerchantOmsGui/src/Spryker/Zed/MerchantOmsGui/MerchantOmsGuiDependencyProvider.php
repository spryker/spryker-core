<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOmsGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantOmsGui\Dependency\Facade\MerchantOmsGuiToMerchantOmsFacadeBridge;

/**
 * @method \Spryker\Zed\MerchantOmsGui\MerchantOmsGuiConfig getConfig()
 */
class MerchantOmsGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_MERCHANT_OMS = 'FACADE_MERCHANT_OMS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addMerchantOmsFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantOmsFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_OMS, function (Container $container) {
            return new MerchantOmsGuiToMerchantOmsFacadeBridge($container->getLocator()->merchantOms()->facade());
        });

        return $container;
    }
}
