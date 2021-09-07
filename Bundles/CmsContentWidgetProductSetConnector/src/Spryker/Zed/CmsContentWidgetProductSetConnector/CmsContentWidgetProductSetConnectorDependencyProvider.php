<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetProductSetConnector;

use Spryker\Zed\CmsContentWidgetProductSetConnector\Dependency\QueryContainer\CmsContentWidgetProductSetConnectorProductSetQueryContainerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CmsContentWidgetProductSetConnector\CmsContentWidgetProductSetConnectorConfig getConfig()
 */
class CmsContentWidgetProductSetConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const QUERY_CONTAINER_PRODUCT_SET = 'PRODUCT SET QUERY CONTAINER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container->set(static::QUERY_CONTAINER_PRODUCT_SET, function (Container $container) {
            return new CmsContentWidgetProductSetConnectorProductSetQueryContainerBridge(
                $container->getLocator()->productSet()->queryContainer()
            );
        });

        return $container;
    }
}
