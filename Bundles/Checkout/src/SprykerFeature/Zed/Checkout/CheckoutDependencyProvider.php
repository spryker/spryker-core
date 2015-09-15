<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Checkout;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;
use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutOrderHydrationInterface;
use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutPostSaveHookInterface;
use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutPreconditionInterface;
use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutPreHydrationInterface;
use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface;

class CheckoutDependencyProvider extends AbstractBundleDependencyProvider
{

    const CHECKOUT_PRECONDITIONS = 'checkout_preconditions';
    const CHECKOUT_PRE_HYDRATOR = 'checkout pre hydrator';
    const CHECKOUT_POSTHOOKS = 'checkout_posthooks';
    const CHECKOUT_ORDERHYDRATORS = 'checkout_orderhydrators';
    const CHECKOUT_ORDERSAVERS = 'checkout_ordersavers';
    const FACADE_OMS = 'oms facade';
    const FACADE_CALCULATION = 'calculation facade';

    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::CHECKOUT_PRECONDITIONS] = function (Container $container) {
            return $this->getCheckoutPreconditions($container);
        };

        $container[self::CHECKOUT_PRE_HYDRATOR] = function (Container $container) {
            return $this->getCheckoutPreHydrator($container);
        };

        $container[self::CHECKOUT_ORDERHYDRATORS] = function (Container $container) {
            return $this->getCheckoutOrderHydrators($container);
        };

        $container[self::CHECKOUT_ORDERSAVERS] = function (Container $container) {
            return $this->getCheckoutOrderSavers($container);
        };

        $container[self::CHECKOUT_POSTHOOKS] = function (Container $container) {
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
     * @return CheckoutPreconditionInterface[]
     */
    protected function getCheckoutPreconditions(Container $container)
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
