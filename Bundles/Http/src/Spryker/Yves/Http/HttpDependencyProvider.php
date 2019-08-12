<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Http;

use Spryker\Yves\Http\Dependency\Client\HttpToLocaleClientBridge;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class HttpDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addLocaleClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container->set(static::CLIENT_LOCALE, function (Container $container) {
            return new HttpToLocaleClientBridge($container->getLocator()->locale()->client());
        });

        return $container;
    }
}
