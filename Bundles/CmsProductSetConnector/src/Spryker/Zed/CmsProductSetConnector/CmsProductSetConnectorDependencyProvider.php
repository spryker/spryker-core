<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsProductSetConnector;

use Spryker\Zed\CmsProductSetConnector\Dependency\QueryContainer\CmsProductSetConnectorProductSetQueryContainerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsProductSetConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const QUERY_CONTAINER_PRODUCT_SET = 'PRODUCT_SET_QUERY_CONTAINER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container[static::QUERY_CONTAINER_PRODUCT_SET] = function (Container $container) {
            return new CmsProductSetConnectorProductSetQueryContainerBridge(
                $container->getLocator()->productSet()->queryContainer()
            );
        };
    }

}
