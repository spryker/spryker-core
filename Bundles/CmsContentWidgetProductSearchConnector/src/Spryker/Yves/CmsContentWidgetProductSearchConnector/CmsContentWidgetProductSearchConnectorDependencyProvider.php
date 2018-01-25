<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidgetProductSearchConnector;

use Spryker\Yves\CmsContentWidgetProductSearchConnector\Dependency\Client\CmsContentWidgetProductSearchConnectorToProductBridge;
use Spryker\Yves\CmsContentWidgetProductSearchConnector\Dependency\Client\CmsContentWidgetProductSearchConnectorToSearchBridge;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class CmsContentWidgetProductSearchConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    const CLIENT_PRODUCT = 'PRODUCT CLIENT';
    const CLIENT_SEARCH = 'SEARCH CLIENT';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container[static::CLIENT_PRODUCT] = function (Container $container) {
            return new CmsContentWidgetProductSearchConnectorToProductBridge(
                $container->getLocator()->product()->client()
            );
        };
        $container[static::CLIENT_SEARCH] = function (Container $container) {
            return new CmsContentWidgetProductSearchConnectorToSearchBridge(
                $container->getLocator()->search()->client()
            );
        };

        return $container;
    }
}
