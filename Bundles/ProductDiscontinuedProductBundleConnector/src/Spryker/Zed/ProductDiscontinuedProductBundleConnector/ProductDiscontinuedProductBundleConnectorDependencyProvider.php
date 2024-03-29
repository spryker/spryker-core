<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductBundleConnector;

use Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinuedQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductDiscontinuedProductBundleConnector\Dependency\Facade\ProductDiscontinuedProductBundleConnectorToProductBundleFacadeBridge;
use Spryker\Zed\ProductDiscontinuedProductBundleConnector\Dependency\Facade\ProductDiscontinuedProductBundleConnectorToProductDiscontinuedFacadeBridge;

/**
 * @method \Spryker\Zed\ProductDiscontinuedProductBundleConnector\ProductDiscontinuedProductBundleConnectorConfig getConfig()
 */
class ProductDiscontinuedProductBundleConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_PRODUCT_DISCONTINUED = 'FACADE_PRODUCT_DISCONTINUED';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_BUNDLE = 'FACADE_PRODUCT_BUNDLE';

    /**
     * @var string
     */
    public const PROPEL_QUERY_PRODUCT_DISCONTINUED = 'PROPEL_QUERY_PRODUCT_DISCONTINUED';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addProductDiscontinuedFacade($container);
        $container = $this->addProductBundleFacade($container);

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
        $container = $this->addProductDiscontinuedQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductDiscontinuedFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_DISCONTINUED, function (Container $container) {
            return new ProductDiscontinuedProductBundleConnectorToProductDiscontinuedFacadeBridge(
                $container->getLocator()->productDiscontinued()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductBundleFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_BUNDLE, function (Container $container) {
            return new ProductDiscontinuedProductBundleConnectorToProductBundleFacadeBridge(
                $container->getLocator()->productBundle()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductDiscontinuedQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_DISCONTINUED, $container->factory(function () {
            return SpyProductDiscontinuedQuery::create();
        }));

        return $container;
    }
}
