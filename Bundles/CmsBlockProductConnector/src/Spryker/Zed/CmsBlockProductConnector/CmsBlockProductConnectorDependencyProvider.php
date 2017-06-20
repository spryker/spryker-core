<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector;

use Spryker\Zed\CmsBlockProductConnector\Dependency\Facade\CmsBlockProductConnectorToLocaleFacadeBridge;
use Spryker\Zed\CmsBlockProductConnector\Dependency\QueryContainer\CmsBlockProductConnectorToProductAbstractQueryContainerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsBlockProductConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_LOCALE = 'CMS_BLOCK_PRODUCT_CONNECTOR:FACADE_LOCALE';

    const QUERY_CONTAINER_PRODUCT_ABSTRACT = 'CMS_BLOCK_PRODUCT_CONNECTOR:QUERY_CONTAINER_PRODUCT_ABSTRACT';

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

}
