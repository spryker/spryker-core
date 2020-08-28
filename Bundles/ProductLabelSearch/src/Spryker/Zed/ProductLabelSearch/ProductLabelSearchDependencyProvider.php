<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch;

use Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToEventBehaviorFacadeBridge;
use Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToProductLabelBridge;
use Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToProductLabelInterface;
use Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToProductPageSearchBridge;
use Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToProductPageSearchInterface;
use Spryker\Zed\ProductLabelSearch\Dependency\Service\ProductLabelSearchToUtilSanitizeServiceBridge;
use Spryker\Zed\ProductLabelSearch\Dependency\Service\ProductLabelSearchToUtilSanitizeServiceInterface;

/**
 * @method \Spryker\Zed\ProductLabelSearch\ProductLabelSearchConfig getConfig()
 */
class ProductLabelSearchDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_UTIL_SANITIZE = 'SERVICE_UTIL_SANITIZE';
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const FACADE_PRODUCT_LABEL = 'FACADE_PRODUCT_LABEL';
    public const FACADE_PRODUCT_PAGE_SEARCH = 'FACADE_PRODUCT_PAGE_SEARCH';
    public const PROPEL_QUERY_PRODUCT_LABEL = 'PROPEL_QUERY_PRODUCT_LABEL';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addUtilSanitizeService($container);
        $container = $this->addEventBehaviorFacade($container);
        $container = $this->addProductLabelFacade($container);
        $container = $this->addProductPageSearchFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addEventBehaviorFacade($container);
        $container = $this->addProductLabelFacade($container);
        $container = $this->addProductPageSearchFacade($container);

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
        $container = $this->addPropelProductLabelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPropelProductLabelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_LABEL, $container->factory(function (): SpyProductLabelQuery {
            return SpyProductLabelQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilSanitizeService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_SANITIZE, function (Container $container): ProductLabelSearchToUtilSanitizeServiceInterface {
            return new ProductLabelSearchToUtilSanitizeServiceBridge(
                $container->getLocator()->utilSanitize()->service()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventBehaviorFacade(Container $container): Container
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container): ProductLabelSearchToEventBehaviorFacadeInterface {
            return new ProductLabelSearchToEventBehaviorFacadeBridge(
                $container->getLocator()->eventBehavior()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductLabelFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_LABEL, function (Container $container): ProductLabelSearchToProductLabelInterface {
            return new ProductLabelSearchToProductLabelBridge(
                $container->getLocator()->productLabel()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductPageSearchFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_PAGE_SEARCH, function (Container $container): ProductLabelSearchToProductPageSearchInterface {
            return new ProductLabelSearchToProductPageSearchBridge(
                $container->getLocator()->productPageSearch()->facade()
            );
        });

        return $container;
    }
}
