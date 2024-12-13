<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantDiscountConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantDiscountConnector\Dependency\Facade\MerchantDiscountConnectorToDiscountFacadeBridge;
use Spryker\Zed\MerchantDiscountConnector\Dependency\Facade\MerchantDiscountConnectorToMerchantFacadeBridge;

/**
 * @method \Spryker\Zed\MerchantDiscountConnector\MerchantDiscountConnectorConfig getConfig()
 */
class MerchantDiscountConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_DISCOUNT = 'FACADE_DISCOUNT';

    /**
     * @var string
     */
    public const FACADE_MERCHANT = 'FACADE_MERCHANT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addDiscountFacade($container);
        $container = $this->addMerchantFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDiscountFacade(Container $container): Container
    {
        $container->set(static::FACADE_DISCOUNT, function (Container $container) {
            return new MerchantDiscountConnectorToDiscountFacadeBridge($container->getLocator()->discount()->facade());
        });

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
            return new MerchantDiscountConnectorToMerchantFacadeBridge($container->getLocator()->merchant()->facade());
        });

        return $container;
    }
}
