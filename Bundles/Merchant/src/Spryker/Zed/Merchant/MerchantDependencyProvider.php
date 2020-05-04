<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant;

use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Merchant\Dependency\Facade\MerchantToEventFacadeBridge;
use Spryker\Zed\Merchant\Dependency\Facade\MerchantToUrlFacadeBridge;
use Spryker\Zed\Merchant\Dependency\Service\MerchantToUtilTextServiceBridge;

/**
 * @method \Spryker\Zed\Merchant\MerchantConfig getConfig()
 */
class MerchantDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_URL = 'FACADE_URL';
    public const FACADE_EVENT = 'FACADE_EVENT';
    public const SERVICE_UTIL_TEXT = 'SERVICE_UTIL_TEXT';

    public const PLUGINS_MERCHANT_POST_CREATE = 'PLUGINS_MERCHANT_POST_CREATE';
    public const PLUGINS_MERCHANT_POST_UPDATE = 'PLUGINS_MERCHANT_POST_UPDATE';
    public const PLUGINS_MERCHANT_EXPANDER = 'PLUGINS_MERCHANT_EXPANDER';

    public const PROPEL_QUERY_URL = 'PROPEL_QUERY_URL';

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
        $container = $this->addEventFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);

        $container = $this->addUrlPropelQuery($container);

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

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventFacade(Container $container): Container
    {
        $container->set(static::FACADE_EVENT, function (Container $container) {
            return new MerchantToEventFacadeBridge($container->getLocator()->event()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUrlPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_URL, $container->factory(function () {
            return SpyUrlQuery::create();
        }));

        return $container;
    }
}
