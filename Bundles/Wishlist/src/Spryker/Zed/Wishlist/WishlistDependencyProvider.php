<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Wishlist;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Wishlist\Business\Operator\Add;
use Spryker\Zed\Wishlist\Business\Operator\Decrease;
use Spryker\Zed\Wishlist\Business\Operator\Increase;
use Spryker\Zed\Wishlist\Business\Operator\Remove;
use Spryker\Zed\Wishlist\Dependency\Facade\WishlistToProductBridge;

class WishlistDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_PRODUCT = 'facade product';
    const PRE_SAVE_PLUGINS = 'pre save plugins';
    const POST_SAVE_PLUGINS = 'post save plugins';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[static::FACADE_PRODUCT] = function (Container $container) {
            return new WishlistToProductBridge($container->getLocator()->product()->facade());
        };

        $container[self::PRE_SAVE_PLUGINS] = function (Container $container) {
            return $this->preSavePlugins($container);
        };

        $container[self::POST_SAVE_PLUGINS] = function (Container $container) {
            return $this->postSavePlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array
     */
    protected function preSavePlugins(Container $container)
    {
        return [
            Add::OPERATION_NAME => [],
            Decrease::OPERATION_NAME => [],
            Increase::OPERATION_NAME => [],
            Remove::OPERATION_NAME => [],
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array
     */
    protected function postSavePlugins(Container $container)
    {
        return [
            Add::OPERATION_NAME => [],
            Decrease::OPERATION_NAME => [],
            Increase::OPERATION_NAME => [],
            Remove::OPERATION_NAME => [],
        ];
    }

}
