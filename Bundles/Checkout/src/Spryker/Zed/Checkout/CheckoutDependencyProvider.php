<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Checkout;

use Spryker\Zed\Checkout\Dependency\Facade\CheckoutToOmsFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Checkout\CheckoutConfig getConfig()
 */
class CheckoutDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CHECKOUT_PRE_CONDITIONS = 'checkout_pre_conditions';

    /**
     * @var string
     */
    public const CHECKOUT_PRE_CONDITIONS_FOR_ORDER_AMENDMENT = 'CHECKOUT_PRE_CONDITIONS_FOR_ORDER_AMENDMENT';

    /**
     * @var string
     */
    public const CHECKOUT_POST_HOOKS = 'checkout_post_hooks';

    /**
     * @var string
     */
    public const CHECKOUT_POST_HOOKS_FOR_ORDER_AMENDMENT = 'CHECKOUT_POST_HOOKS_FOR_ORDER_AMENDMENT';

    /**
     * @var string
     */
    public const CHECKOUT_ORDER_SAVERS = 'checkout_order_savers';

    /**
     * @var string
     */
    public const CHECKOUT_ORDER_SAVERS_FOR_ORDER_AMENDMENT = 'CHECKOUT_ORDER_SAVERS_FOR_ORDER_AMENDMENT';

    /**
     * @var string
     */
    public const CHECKOUT_PRE_SAVE_HOOKS = 'checkout_pre_save_hooks';

    /**
     * @var string
     */
    public const CHECKOUT_PRE_SAVE_HOOKS_FOR_ORDER_AMENDMENT = 'CHECKOUT_PRE_SAVE_HOOKS_FOR_ORDER_AMENDMENT';

    /**
     * @var string
     */
    public const FACADE_OMS = 'FACADE_OMS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container->set(static::CHECKOUT_PRE_CONDITIONS, function (Container $container) {
            return $this->getCheckoutPreConditions($container);
        });

        $container->set(static::CHECKOUT_PRE_CONDITIONS_FOR_ORDER_AMENDMENT, function (Container $container) {
            return $this->getCheckoutPreConditionsForOrderAmendment($container);
        });

        $container->set(static::CHECKOUT_ORDER_SAVERS, function (Container $container) {
            return $this->getCheckoutOrderSavers($container);
        });

        $container->set(static::CHECKOUT_ORDER_SAVERS_FOR_ORDER_AMENDMENT, function (Container $container) {
            return $this->getCheckoutOrderSaversForOrderAmendment($container);
        });

        $container->set(static::CHECKOUT_POST_HOOKS, function (Container $container) {
            return $this->getCheckoutPostHooks($container);
        });

        $container->set(static::CHECKOUT_POST_HOOKS_FOR_ORDER_AMENDMENT, function (Container $container) {
            return $this->getCheckoutPostHooksForOrderAmendment($container);
        });

        $container->set(static::CHECKOUT_PRE_SAVE_HOOKS, function (Container $container) {
            return $this->getCheckoutPreSaveHooks($container);
        });

        $container->set(static::CHECKOUT_PRE_SAVE_HOOKS_FOR_ORDER_AMENDMENT, function (Container $container) {
            return $this->getCheckoutPreSaveHooksForOrderAmendment($container);
        });

        $container = $this->addOmsFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOmsFacade(Container $container)
    {
        $container->set(static::FACADE_OMS, function () use ($container) {
            return new CheckoutToOmsFacadeBridge($container->getLocator()->oms()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return list<\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreConditionPluginInterface>
     */
    protected function getCheckoutPreConditions(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return list<\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreConditionPluginInterface>
     */
    protected function getCheckoutPreConditionsForOrderAmendment(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return list<\Spryker\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface|\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface>
     */
    protected function getCheckoutOrderSavers(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return list<\Spryker\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface|\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface>
     */
    protected function getCheckoutOrderSaversForOrderAmendment(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return list<\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPostSaveInterface>
     */
    protected function getCheckoutPostHooks(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return list<\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPostSaveInterface>
     */
    protected function getCheckoutPostHooksForOrderAmendment(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return list<\Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreSaveHookInterface|\Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreSaveInterface|\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreSavePluginInterface>
     */
    protected function getCheckoutPreSaveHooks(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return list<\Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreSaveHookInterface|\Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreSaveInterface|\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreSavePluginInterface>
     */
    protected function getCheckoutPreSaveHooksForOrderAmendment(Container $container)
    {
        return [];
    }
}
