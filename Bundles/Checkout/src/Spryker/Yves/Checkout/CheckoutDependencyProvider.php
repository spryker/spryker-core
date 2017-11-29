<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Checkout;

use Spryker\Yves\Checkout\Dependency\Client\CheckoutToQuoteBridge;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;

class CheckoutDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @see \Spryker\Shared\Application\ApplicationConstants::FORM_FACTORY
     */
    const FORM_FACTORY = 'FORM_FACTORY';

    const PAYMENT_METHOD_HANDLER = 'payment method handler';
    const PAYMENT_SUB_FORMS = 'payment sub forms';

    const PLUGIN_PAYMENT_FILTERS = 'PLUGIN_PAYMENT_FILTERS';
    const PLUGIN_APPLICATION = 'application plugin';

    const CLIENT_QUOTE = 'cart client';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->providePlugins($container);
        $container = $this->provideClients($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function providePlugins(Container $container)
    {
        $container = $this->addPaymentSubForms($container);
        $container = $this->addPaymentFormFilterPlugins($container);
        $container = $this->addPaymentMethodHandler($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPaymentFormFilterPlugins(Container $container)
    {
        $container[static::PLUGIN_PAYMENT_FILTERS] = function () {
            return $this->getPaymentFormFilterPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Yves\Checkout\Dependency\Plugin\Form\SubFormFilterPluginInterface[]
     */
    protected function getPaymentFormFilterPlugins()
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPaymentMethodHandler(Container $container)
    {
        $container[static::PAYMENT_METHOD_HANDLER] = function () {
            return new StepHandlerPluginCollection();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPaymentSubForms(Container $container)
    {
        $container[static::PAYMENT_SUB_FORMS] = function () {
            return new SubFormPluginCollection();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function provideClients(Container $container)
    {
        $container[static::CLIENT_QUOTE] = function () use ($container) {
            return new CheckoutToQuoteBridge($container->getLocator()->quote()->client());
        };

        return $container;
    }
}
