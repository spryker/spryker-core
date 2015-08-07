<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;
use SprykerFeature\Zed\Wishlist\Business\Operator\Add;
use SprykerFeature\Zed\Wishlist\Business\Operator\Decrease;
use SprykerFeature\Zed\Wishlist\Business\Operator\Increase;
use SprykerFeature\Zed\Wishlist\Business\Operator\Remove;

class WishlistDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_PRODUCT = 'facade product';
    const PRE_SAVE_PLUGINS = 'pre save plugins';
    const POST_SAVE_PLUGINS = 'post save plugins';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[static::FACADE_PRODUCT] = function (Container $container) {
            return $container->getLocator()->product()->facade();
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
     * @param Container $container
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
     * @param Container $container
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
