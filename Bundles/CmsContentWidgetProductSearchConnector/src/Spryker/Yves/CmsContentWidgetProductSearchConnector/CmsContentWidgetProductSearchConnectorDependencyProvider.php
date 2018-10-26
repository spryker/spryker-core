<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidgetProductSearchConnector;

use Spryker\Yves\CmsContentWidgetProductSearchConnector\Dependency\Client\CmsContentWidgetProductSearchConnectorToProductClientBridge;
use Spryker\Yves\CmsContentWidgetProductSearchConnector\Dependency\Client\CmsContentWidgetProductSearchConnectorToSearchClientBridge;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class CmsContentWidgetProductSearchConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT = 'PRODUCT CLIENT';
    public const CLIENT_SEARCH = 'SEARCH CLIENT';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addProductClient($container);
        $container = $this->addSearchClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductClient(Container $container): Container
    {
        $container[static::CLIENT_PRODUCT] = function (Container $container) {
            return new CmsContentWidgetProductSearchConnectorToProductClientBridge(
                $container->getLocator()->product()->client()
            );
        };
        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSearchClient(Container $container): Container
    {
        $container[static::CLIENT_SEARCH] = function (Container $container) {
            return new CmsContentWidgetProductSearchConnectorToSearchClientBridge(
                $container->getLocator()->search()->client()
            );
        };
        return $container;
    }
}
