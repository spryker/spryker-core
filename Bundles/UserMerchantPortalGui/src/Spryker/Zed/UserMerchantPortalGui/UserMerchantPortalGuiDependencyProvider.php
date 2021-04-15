<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserMerchantPortalGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToLocaleFacadeBridge;
use Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToMerchantUserFacadeBridge;
use Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToTranslatorFacadeBridge;

class UserMerchantPortalGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_MERCHANT_USER = 'FACADE_MERCHANT_USER';
    public const FACADE_TRANSLATOR = 'FACADE_TRANSLATOR';

    public const PLUGINS_MERCHANT_USER_POST_CHANGE_PLUGINS = 'PLUGINS_MERCHANT_USER_POST_CHANGE_PLUGINS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        parent::provideCommunicationLayerDependencies($container);

        $container = $this->addLocaleFacade($container);
        $container = $this->addMerchantUserFacade($container);
        $container = $this->addTranslatorFacade($container);

        $container = $this->addMerchantUserPostChangePlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new UserMerchantPortalGuiToLocaleFacadeBridge($container->getLocator()->locale()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_USER, function (Container $container) {
            return new UserMerchantPortalGuiToMerchantUserFacadeBridge($container->getLocator()->merchantUser()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTranslatorFacade(Container $container): Container
    {
        $container->set(static::FACADE_TRANSLATOR, function (Container $container) {
            return new UserMerchantPortalGuiToTranslatorFacadeBridge($container->getLocator()->translator()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantUserPostChangePlugin(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_USER_POST_CHANGE_PLUGINS, function () {
            return $this->getMerchantUserPostChangePlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\UserMerchantPortalGuiExtension\Dependency\Plugin\MerchantUserPostChangePluginInterface[]
     */
    public function getMerchantUserPostChangePlugins(): array
    {
        return [];
    }
}
