<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileMerchantPortalGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToCountryFacadeBridge;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToGlossaryFacadeBridge;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToLocaleFacadeBridge;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToMerchantFacadeBridge;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToMerchantUserFacadeBridge;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToUrlFacadeBridge;

/**
 * @method \Spryker\Zed\MerchantProfileMerchantPortalGui\MerchantProfileMerchantPortalGuiConfig getConfig()
 */
class MerchantProfileMerchantPortalGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_MERCHANT = 'FACADE_MERCHANT';
    public const FACADE_MERCHANT_USER = 'FACADE_MERCHANT_USER';
    public const FACADE_GLOSSARY = 'FACADE_GLOSSARY';
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_URL = 'FACADE_URL';
    public const FACADE_COUNTRY = 'FACADE_COUNTRY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addMerchantFacade($container);
        $container = $this->addMerchantUserFacade($container);
        $container = $this->addGlossaryFacade($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addUrlFacade($container);
        $container = $this->addCountryFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT, function (Container $container) {
            return new MerchantProfileMerchantPortalGuiToMerchantFacadeBridge($container->getLocator()->merchant()->facade());
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
            return new MerchantProfileMerchantPortalGuiToMerchantUserFacadeBridge($container->getLocator()->merchantUser()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGlossaryFacade(Container $container): Container
    {
        $container->set(static::FACADE_GLOSSARY, function (Container $container) {
            return new MerchantProfileMerchantPortalGuiToGlossaryFacadeBridge($container->getLocator()->glossary()->facade());
        });

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
            return new MerchantProfileMerchantPortalGuiToLocaleFacadeBridge($container->getLocator()->locale()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUrlFacade(Container $container): Container
    {
        $container->set(static::FACADE_URL, function (Container $container) {
            return new MerchantProfileMerchantPortalGuiToUrlFacadeBridge($container->getLocator()->url()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCountryFacade(Container $container): Container
    {
        $container->set(static::FACADE_COUNTRY, function (Container $container) {
            return new MerchantProfileMerchantPortalGuiToCountryFacadeBridge($container->getLocator()->country()->facade());
        });

        return $container;
    }
}
