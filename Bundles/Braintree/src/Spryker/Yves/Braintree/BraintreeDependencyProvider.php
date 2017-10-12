<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Braintree;

use Spryker\Yves\Currency\Plugin\CurrencyPlugin;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class BraintreeDependencyProvider extends AbstractBundleDependencyProvider
{
    const PLUGIN_CURRENCY = 'currency plugin';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container|\Spryker\Zed\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addCurrencyPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCurrencyPlugin(Container $container)
    {
        $container[static::PLUGIN_CURRENCY] = function (Container $container) {
            return new CurrencyPlugin();
        };

        return $container;
    }
}
