<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsContentWidget;

use Spryker\Client\CmsContentWidget\Dependency\Client\CmsContentWidgetToZedRequestBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class CmsContentWidgetDependencyProvider extends AbstractDependencyProvider
{

    const SERVICE_ZED = 'SERVICE_ZED';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[static::SERVICE_ZED] = function (Container $container) {
            return new CmsContentWidgetToZedRequestBridge($container->getLocator()->zedRequest()->client());
        };

        return $container;
    }

}
