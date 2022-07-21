<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleDiscountConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductBundleDiscountConnector\Dependency\Facade\ProductBundleDiscountConnectorToDiscountFacadeBridge;
use Spryker\Zed\ProductBundleDiscountConnector\Dependency\Facade\ProductBundleDiscountConnectorToLocaleFacadeBridge;
use Spryker\Zed\ProductBundleDiscountConnector\Dependency\Facade\ProductBundleDiscountConnectorToProductFacadeBridge;

/**
 * @method \Spryker\Zed\ProductBundleDiscountConnector\ProductBundleDiscountConnectorConfig getConfig()
 */
class ProductBundleDiscountConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_DISCOUNT = 'FACADE_DISCOUNT';

    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @var string
     */
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addDiscountFacade($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addProductFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT, function (Container $container) {
            return new ProductBundleDiscountConnectorToProductFacadeBridge(
                $container->getLocator()->product()->facade(),
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
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new ProductBundleDiscountConnectorToLocaleFacadeBridge(
                $container->getLocator()->locale()->facade(),
            );
        });

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
            return new ProductBundleDiscountConnectorToDiscountFacadeBridge(
                $container->getLocator()->discount()->facade(),
            );
        });

        return $container;
    }
}
