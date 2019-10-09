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
    public const PLUGINS_MERCHANT_EXPANDER = 'PLUGINS_MERCHANT_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addUtilTextService($container);
        $container = $this->addMerchantPostSavePlugins($container);
        $container = $this->addMerchantExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilTextService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_TEXT, function (Container $container) {
            return new MerchantToUtilTextServiceBridge($container->getLocator()->utilText()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantPostSavePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_POST_SAVE, function () {
            return $this->getMerchantPostSavePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_EXPANDER, function () {
            return $this->getMerchantExpanderPlugins();
        });

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
     * @return \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantExpanderPluginInterface[]
     */
    protected function getMerchantExpanderPlugins(): array
    {
        return [];
    }
}
