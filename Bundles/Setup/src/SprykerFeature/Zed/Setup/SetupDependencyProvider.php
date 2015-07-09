<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Setup;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class SetupDependencyProvider extends AbstractBundleDependencyProvider
{

    const PLUGIN_TRANSFER_OBJECT_REPEATER = 'plugin transfer object repeater';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::PLUGIN_TRANSFER_OBJECT_REPEATER] = function (Container $container) {
            return $container->getLocator()->application()->pluginTransferObjectRepeater();
        };

        return $container;
    }

}
