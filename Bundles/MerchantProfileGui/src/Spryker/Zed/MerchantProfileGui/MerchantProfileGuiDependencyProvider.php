<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Communication\Plugin\Pimple;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantProfileGui\Dependency\Facade\MerchantProfileGuiToMerchantProfileFacadeBridge;

/**
 * @method \Spryker\Zed\MerchantProfileGui\MerchantProfileGuiConfig getConfig()
 */
class MerchantProfileGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const TWIG_ENVIRONMENT = 'TWIG_ENVIRONMENT';
    public const FACADE_MERCHANT_PROFILE = 'FACADE_MERCHANT_PROFILE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addTwigEnvironment($container);
        $container = $this->addMerchantProfileFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTwigEnvironment(Container $container): Container
    {
        $container[static::TWIG_ENVIRONMENT] = function () {
            return (new Pimple())->getApplication()['twig'];
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantProfileFacade(Container $container): Container
    {
        $container[static::FACADE_MERCHANT_PROFILE] = function (Container $container) {
            return new MerchantProfileGuiToMerchantProfileFacadeBridge($container->getLocator()->merchantProfile()->facade());
        };

        return $container;
    }
}
