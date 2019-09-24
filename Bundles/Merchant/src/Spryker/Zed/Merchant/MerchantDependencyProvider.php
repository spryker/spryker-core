<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Merchant\Dependency\Service\MerchantToUtilTextServiceBridge;

/**
 * @method \Spryker\Zed\Merchant\MerchantConfig getConfig()
 */
class MerchantDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_UTIL_TEXT = 'SERVICE_UTIL_TEXT';
    public const PLUGINS_MERCHANT_POST_SAVE = 'PLUGINS_MERCHANT_POST_SAVE';
    public const PLUGINS_MERCHANT_HYDRATE = 'PLUGINS_MERCHANT_HYDRATE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addUtilTextService($container);
        $container = $this->addMerchantPostSavePlugins($container);
        $container = $this->addMerchantHydratePlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilTextService(Container $container): Container
    {
        $container[static::SERVICE_UTIL_TEXT] = function (Container $container) {
            return new MerchantToUtilTextServiceBridge($container->getLocator()->utilText()->service());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantPostSavePlugins(Container $container): Container
    {
        $container[static::PLUGINS_MERCHANT_POST_SAVE] = function () {
            return $this->getMerchantPostSavePlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantHydratePlugins(Container $container): Container
    {
        $container[static::PLUGINS_MERCHANT_HYDRATE] = function () {
            return $this->getMerchantHydratePlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostSavePluginInterface[]
     */
    protected function getMerchantPostSavePlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantHydrationPluginInterface[]
     */
    protected function getMerchantHydratePlugins(): array
    {
        return [];
    }
}
