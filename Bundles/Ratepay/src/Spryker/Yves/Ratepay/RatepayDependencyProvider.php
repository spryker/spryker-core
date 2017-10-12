<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Ratepay;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class RatepayDependencyProvider extends AbstractBundleDependencyProvider
{
    const CLIENT_RATEPAY = 'ratepay client';
    const CLIENT_SESSION = 'session client';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container[self::CLIENT_RATEPAY] = function (Container $container) {
            return $container->getLocator()->ratepay()->client();
        };

        $container[self::CLIENT_SESSION] = function (Container $container) {
            return $container->getLocator()->session()->client();
        };

        return $container;
    }
}
