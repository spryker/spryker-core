<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Merchant\Dependency\Facade\MerchantToUrlFacadeBridge;
use Spryker\Zed\Merchant\Dependency\Service\MerchantToUtilTextServiceBridge;

/**
 * @method \Spryker\Zed\Merchant\MerchantConfig getConfig()
 */
class MerchantDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_URL = 'FACADE_URL';
    public const SERVICE_UTIL_TEXT = 'SERVICE_UTIL_TEXT';

    public const PLUGINS_MERCHANT_POST_CREATE = 'PLUGINS_MERCHANT_POST_CREATE';
    public const PLUGINS_MERCHANT_POST_UPDATE = 'PLUGINS_MERCHANT_POST_UPDATE';
    public const PLUGINS_MERCHANT_EXPANDER = 'PLUGINS_MERCHANT_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addUtilTextService($container);
        $container = $this->addMerchantPostCreatePlugins($container);
        $container = $this->addMerchantPostUpdatePlugins($container);
        $container = $this->addMerchantExpanderPlugins($container);
        $container = $this->addUrlFacade($container);

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
    protected function addMerchantPostCreatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_POST_CREATE, function () {
            return $this->getMerchantPostCreatePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantPostUpdatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_POST_UPDATE, function () {
            return $this->getMerchantPostUpdatePlugins();
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
     * @return \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostUpdatePluginInterface[]
     */
    protected function getMerchantPostUpdatePlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostCreatePluginInterface[]
     */
    protected function getMerchantPostCreatePlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Use MerchantDependencyProvider::getMerchantPostCreatePlugins() or getMerchantPostUpdatePlugins() instead.
     *
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

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUrlFacade(Container $container): Container
    {
        $container->set(static::FACADE_URL, function (Container $container) {
            return new MerchantToUrlFacadeBridge($container->getLocator()->url()->facade());
        });

        return $container;
    }
}
