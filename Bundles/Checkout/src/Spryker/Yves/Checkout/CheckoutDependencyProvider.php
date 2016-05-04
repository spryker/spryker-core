<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Checkout;

use Spryker\Yves\Checkout\Dependency\Plugin\CheckoutStepHandlerPluginCollection;
use Spryker\Yves\Checkout\Dependency\Plugin\CheckoutSubFormPluginCollection;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class CheckoutDependencyProvider extends AbstractBundleDependencyProvider
{

    const PAYMENT_METHOD_HANDLER = 'payment method handler';
    const PAYMENT_SUB_FORMS = 'payment sub forms';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->providePlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function providePlugins(Container $container)
    {
        $container[self::PAYMENT_SUB_FORMS] = function () {
            return new CheckoutSubFormPluginCollection();
        };

        $container[self::PAYMENT_METHOD_HANDLER] = function () {
            return new CheckoutStepHandlerPluginCollection();
        };

        return $container;
    }

}
