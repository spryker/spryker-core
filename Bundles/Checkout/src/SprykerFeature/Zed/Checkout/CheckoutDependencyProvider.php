<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Checkout;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;
use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutOrderHydrationInterface;
use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutPostSaveHookInterface;
use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface;
use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutPreHydrationInterface;
use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface;

class CheckoutDependencyProvider extends AbstractBundleDependencyProvider
{

    const CHECKOUT_PRE_CONDITIONS = 'checkout_pre_conditions';
    const CHECKOUT_PRE_HYDRATOR = 'checkout_pre_hydrator';
    const CHECKOUT_POST_HOOKS = 'checkout_post_hooks';
    const CHECKOUT_ORDER_HYDRATORS = 'checkout_order_hydrators';
    const CHECKOUT_ORDER_SAVERS = 'checkout_order_savers';
    const FACADE_OMS = 'oms facade';
    const FACADE_CALCULATION = 'calculation facade';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::CHECKOUT_PRE_CONDITIONS] = function (Container $container) {
            return $this->getCheckoutPreConditions($container);
        };

        $container[self::CHECKOUT_PRE_HYDRATOR] = function (Container $container) {
            return $this->getCheckoutPreHydrator($container);
        };

        $container[self::CHECKOUT_ORDER_HYDRATORS] = function (Container $container) {
            return $this->getCheckoutOrderHydrators($container);
        };

        $container[self::CHECKOUT_ORDER_SAVERS] = function (Container $container) {
            return $this->getCheckoutOrderSavers($container);
        };

        $container[self::CHECKOUT_POST_HOOKS] = function (Container $container) {
            return $this->getCheckoutPostHooks($container);
        };

        $container[self::FACADE_OMS] = function (Container $container) {
            return $container->getLocator()->oms()->facade();
        };

        $container[self::FACADE_CALCULATION] = function (Container $container) {
            return $container->getLocator()->calculation()->facade();
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return CheckoutPreConditionInterface[]
     */
    protected function getCheckoutPreConditions(Container $container)
    {
        return [];
    }

    /**
     * @param Container $container
     *
     * @return CheckoutPreHydrationInterface[]
     */
    protected function getCheckoutPreHydrator(Container $container)
    {
        return [];
    }

    /**
     * @param Container $container
     *
     * @return CheckoutOrderHydrationInterface[]
     */
    protected function getCheckoutOrderHydrators(Container $container)
    {
        return [];
    }

    /**
     * @param Container $container
     *
     * @return CheckoutSaveOrderInterface[]
     */
    protected function getCheckoutOrderSavers(Container $container)
    {
        return [];
    }

    /**
     * @param Container $container
     *
     * @return CheckoutPostSaveHookInterface[]
     */
    protected function getCheckoutPostHooks(Container $container)
    {
        return [];
    }

}
