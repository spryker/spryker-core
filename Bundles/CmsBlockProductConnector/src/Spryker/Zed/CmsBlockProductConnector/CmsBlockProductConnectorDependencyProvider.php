<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector;

use Spryker\Zed\CmsBlockProductConnector\Dependency\Facade\CmsBlockProductConnectorToCollectorBridge;
use Spryker\Zed\CmsBlockProductConnector\Dependency\Facade\CmsBlockProductConnectorToLocaleBridge;
use Spryker\Zed\CmsBlockProductConnector\Dependency\Facade\CmsBlockProductConnectorToProductFacadeBridge;
use Spryker\Zed\CmsBlockProductConnector\Dependency\Facade\CmsBlockProductConnectorToTouchBridge;
use Spryker\Zed\CmsBlockProductConnector\Dependency\QueryContainer\CmsBlockProductConnectorToProductAbstractQueryContainerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CmsBlockProductConnector\CmsBlockProductConnectorConfig getConfig()
 */
class CmsBlockProductConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_LOCALE = 'CMS_BLOCK_PRODUCT_CONNECTOR:FACADE_LOCALE';
    public const FACADE_TOUCH = 'CMS_BLOCK_PRODUCT_CONNECTOR:FACADE_TOUCH';
    public const FACADE_COLLECTOR = 'CMS_BLOCK_PRODUCT_CONNECTOR:FACADE_COLLECTOR';
    public const FACADE_PRODUCT = 'CMS_BLOCK_PRODUCT_CONNECTOR:FACADE_PRODUCT';

    public const QUERY_CONTAINER_PRODUCT_ABSTRACT = 'CMS_BLOCK_PRODUCT_CONNECTOR:QUERY_CONTAINER_PRODUCT_ABSTRACT';
    public const QUERY_CONTAINER_TOUCH = 'CMS_BLOCK_PRODUCT_CONNECTOR:QUERY_CONTAINER_TOUCH';

    public const SERVICE_DATA_READER = 'CMS_BLOCK_PRODUCT_CONNECTOR:SERVICE_DATA_READER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addProductAbstractQueryContainer($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addProductFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addTouchFacade($container);
        $container = $this->addTouchQueryContainer($container);
        $container = $this->addCollectorFacade($container);
        $container = $this->addDataReaderService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container = parent::providePersistenceLayerDependencies($container);
        $container = $this->addProductAbstractQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container)
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new CmsBlockProductConnectorToLocaleBridge($container->getLocator()->locale()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductFacade(Container $container): Container
    {
        $container[static::FACADE_PRODUCT] = function (Container $container) {
            return new CmsBlockProductConnectorToProductFacadeBridge($container->getLocator()->product()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_PRODUCT_ABSTRACT] = function (Container $container) {
            return new CmsBlockProductConnectorToProductAbstractQueryContainerBridge($container->getLocator()->product()->queryContainer());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTouchFacade(Container $container)
    {
        $container[static::FACADE_TOUCH] = function (Container $container) {
            return new CmsBlockProductConnectorToTouchBridge($container->getLocator()->touch()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCollectorFacade(Container $container)
    {
        $container[static::FACADE_COLLECTOR] = function (Container $container) {
            return new CmsBlockProductConnectorToCollectorBridge($container->getLocator()->collector()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDataReaderService(Container $container)
    {
        $container[static::SERVICE_DATA_READER] = function (Container $container) {
            return $container->getLocator()->utilDataReader()->service();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTouchQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_TOUCH] = function (Container $container) {
            return $container->getLocator()->touch()->queryContainer();
        };

        return $container;
    }
}
