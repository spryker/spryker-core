<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payment;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class PaymentDependencyProvider extends AbstractBundleDependencyProvider
{
    const CHECKOUT_PLUGINS = 'checkout plugins';
    const CHECKOUT_PRE_CHECK_PLUGINS = 'pre check';
    const CHECKOUT_ORDER_SAVER_PLUGINS = 'order saver';
    const CHECKOUT_POST_SAVE_PLUGINS = 'post save';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::CHECKOUT_PLUGINS] = function (Container $container) {
            return $this->getCheckoutPlugins($container);
        };

        return $container;
    }

    /**
     * @param Container $container
     * @return array
     */
    protected function getCheckoutPlugins(Container $container)
    {
        return [
            self::CHECKOUT_PRE_CHECK_PLUGINS => [],
            self::CHECKOUT_ORDER_SAVER_PLUGINS => [],
            self::CHECKOUT_POST_SAVE_PLUGINS => [],
        ];
    }
}
