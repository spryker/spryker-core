<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle;

use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToGlossaryFacadeBridge;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToGlossaryFacadeInterface;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToLocaleFacadeBridge;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToLocaleFacadeInterface;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToProductListFacadeBridge;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToProductListFacadeInterface;
use Spryker\Zed\ConfigurableBundle\Dependency\Service\ConfigurableBundleToUtilTextServiceBridge;
use Spryker\Zed\ConfigurableBundle\Dependency\Service\ConfigurableBundleToUtilTextServiceInterface;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\ConfigurableBundle\ConfigurableBundleConfig getConfig()
 */
class ConfigurableBundleDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_GLOSSARY = 'FACADE_GLOSSARY';
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_PRODUCT_LIST = 'FACADE_PRODUCT_LIST';

    public const SERVICE_UTIL_TEXT = 'SERVICE_UTIL_TEXT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addGlossaryFacade($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addProductListFacade($container);
        $container = $this->addUtilTextService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGlossaryFacade(Container $container): Container
    {
        $container->set(static::FACADE_GLOSSARY, function (Container $container): ConfigurableBundleToGlossaryFacadeInterface {
            return new ConfigurableBundleToGlossaryFacadeBridge(
                $container->getLocator()->glossary()->facade()
            );
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
        $container->set(static::FACADE_LOCALE, function (Container $container): ConfigurableBundleToLocaleFacadeInterface {
            return new ConfigurableBundleToLocaleFacadeBridge(
                $container->getLocator()->locale()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductListFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_LIST, function (Container $container): ConfigurableBundleToProductListFacadeInterface {
            return new ConfigurableBundleToProductListFacadeBridge(
                $container->getLocator()->productList()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilTextService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_TEXT, function (Container $container): ConfigurableBundleToUtilTextServiceInterface {
            return new ConfigurableBundleToUtilTextServiceBridge(
                $container->getLocator()->utilText()->service()
            );
        });

        return $container;
    }
}
