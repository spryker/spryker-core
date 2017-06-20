<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector;

use function foo\func;
use Spryker\Zed\CmsBlockProductConnector\Dependency\Facade\CmsBlockProductConnectorToCollectorFacadeBridge;
use Spryker\Zed\CmsBlockProductConnector\Dependency\Facade\CmsBlockProductConnectorToLocaleFacadeBridge;
use Spryker\Zed\CmsBlockProductConnector\Dependency\Facade\CmsBlockProductConnectorToTouchFacadeBridge;
use Spryker\Zed\CmsBlockProductConnector\Dependency\QueryContainer\CmsBlockProductConnectorToProductAbstractQueryContainerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsBlockProductConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_LOCALE = 'CMS_BLOCK_PRODUCT_CONNECTOR:FACADE_LOCALE';
    const FACADE_TOUCH = 'CMS_BLOCK_PRODUCT_CONNECTOR:FACADE_TOUCH';
    const FACADE_COLLECTOR = 'CMS_BLOCK_PRODUCT_CONNECTOR:FACADE_COLLECTOR';

    const QUERY_CONTAINER_PRODUCT_ABSTRACT = 'CMS_BLOCK_PRODUCT_CONNECTOR:QUERY_CONTAINER_PRODUCT_ABSTRACT';
    const QUERY_CONTAINER_TOUCH = 'CMS_BLOCK_PRODUCT_CONNECTOR:QUERY_CONTAINER_TOUCH';

    const SERVICE_DATA_READER = 'CMS_BLOCK_PRODUCT_CONNECTOR:SERVICE_DATA_READER';

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

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
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
    protected function addLocaleFacade(Container $container)
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new CmsBlockProductConnectorToLocaleFacadeBridge($container->getLocator()->locale()->facade());
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
            return new CmsBlockProductConnectorToTouchFacadeBridge($container->getLocator()->touch()->facade());
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addCollectorFacade(Container $container)
    {
        $container[static::FACADE_COLLECTOR] = function (Container $container) {
            return new CmsBlockProductConnectorToCollectorFacadeBridge($container->getLocator()->collector()->facade());
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
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
