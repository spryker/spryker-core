<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsProductConnector;

use Spryker\Zed\CmsProductConnector\Dependency\QueryContainer\CmsProductConnectorProductQueryContainerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsProductConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const QUERY_CONTAINER_PRODUCT = 'PRODUCT_QUERY_CONTAINER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container[static::QUERY_CONTAINER_PRODUCT] = function (Container $container) {
            return new CmsProductConnectorProductQueryContainerBridge(
                $container->getLocator()->product()->queryContainer()
            );
        };
    }

}
