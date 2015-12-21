<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Setup;

use Spryker\Zed\Application\Communication\Plugin\TransferObject\Repeater;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Symfony\Component\Console\Command\Command;

class SetupDependencyProvider extends AbstractBundleDependencyProvider
{

    const PLUGIN_TRANSFER_OBJECT_REPEATER = 'plugin transfer object repeater';

    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::PLUGIN_TRANSFER_OBJECT_REPEATER] = function () {
            return new Repeater();
        };

        return $container;
    }

}
