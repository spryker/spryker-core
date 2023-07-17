<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageCartConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductImageCartConnector\Dependency\Facade\ProductImageCartConnectorToLocaleFacadeBridge;
use Spryker\Zed\ProductImageCartConnector\Dependency\Facade\ProductImageCartConnectorToProductImageFacadeBridge;

/**
 * @method \Spryker\Zed\ProductImageCartConnector\ProductImageCartConnectorConfig getConfig()
 */
class ProductImageCartConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_PRODUCT_IMAGE = 'FACADE_PRODUCT_IMAGE';

    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addProductImageFacade($container);
        $container = $this->addLocaleFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductImageFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_IMAGE, function (Container $container) {
            return new ProductImageCartConnectorToProductImageFacadeBridge($container->getLocator()->productImage()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade($container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new ProductImageCartConnectorToLocaleFacadeBridge($container->getLocator()->locale()->facade());
        });

        return $container;
    }
}
