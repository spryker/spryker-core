<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetProductConnector;

use Spryker\Zed\CmsContentWidgetProductConnector\Dependency\QueryContainer\CmsContentWidgetProductConnectorToProductBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CmsContentWidgetProductConnector\CmsContentWidgetProductConnectorConfig getConfig()
 */
class CmsContentWidgetProductConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const QUERY_CONTAINER_PRODUCT = 'PRODUCT_QUERY_CONTAINER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container->set(static::QUERY_CONTAINER_PRODUCT, function (Container $container) {
            return new CmsContentWidgetProductConnectorToProductBridge(
                $container->getLocator()->product()->queryContainer(),
            );
        });

        return $container;
    }
}
