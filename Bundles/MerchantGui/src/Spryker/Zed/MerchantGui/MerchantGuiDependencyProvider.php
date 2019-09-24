<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\MerchantGui;

use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToCountryFacadeBridge;
use Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeBridge;

/**
 * @method \Spryker\Zed\MerchantGui\MerchantGuiConfig getConfig()
 */
class MerchantGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_COUNTRY = 'FACADE_COUNTRY';
    public const FACADE_MERCHANT = 'FACADE_MERCHANT';
    public const PROPEL_MERCHANT_QUERY = 'PROPEL_MERCHANT_QUERY';
    public const PLUGINS_MERCHANT_PROFILE_FORM_EXPANDER = 'PLUGINS_MERCHANT_PROFILE_FORM_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addCountryFacade($container);
        $container = $this->addMerchantFacade($container);
        $container = $this->addPropelMerchantQuery($container);
        $container = $this->addMerchantProfileFormExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCountryFacade(Container $container): Container
    {
        $container[static::FACADE_COUNTRY] = function (Container $container) {
            return new MerchantGuiToCountryFacadeBridge($container->getLocator()->country()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantFacade(Container $container): Container
    {
        $container[static::FACADE_MERCHANT] = function (Container $container) {
            return new MerchantGuiToMerchantFacadeBridge($container->getLocator()->merchant()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPropelMerchantQuery(Container $container): Container
    {
        $container[static::PROPEL_MERCHANT_QUERY] = function () {
            return SpyMerchantQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantProfileFormExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_MERCHANT_PROFILE_FORM_EXPANDER] = function () {
            return $this->getMerchantProfileFormExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantFormExpanderPluginInterface[]
     */
    protected function getMerchantProfileFormExpanderPlugins(): array
    {
        return [];
    }
}
