<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui;

use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\ZedExtensionServiceProvider;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class GuiDependencyProvider extends AbstractBundleDependencyProvider
{
    const GUI_SERVICE_PROVIDER = 'GUI_SERVICE_PROVIDER';

    public function provideCommunicationLayerDependencies(Container $container)
    {
        dump('a');
        die;

        $container[self::GUI_SERVICE_PROVIDER] = function(Container $container){
            return $this->getServiceProvider($container);
        };

        return $container;
    }

    protected function getServiceProvider(Container $container)
    {
        $providers = [
            new ZedExtensionServiceProvider(),
        ];

        return $providers;
    }

}
