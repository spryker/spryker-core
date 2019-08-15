<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlot;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class CmsSlotDependencyProvider extends AbstractDependencyProvider
{
    public const CMS_SLOT_FILLER_STRATEGY_PLUGINS = 'CMS_SLOT_FILLER_STRATEGY_PLUGINS';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = $this->addCmsSlotFillerStrategyPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCmsSlotFillerStrategyPlugin(Container $container): Container
    {
        $container->set(static::CMS_SLOT_FILLER_STRATEGY_PLUGINS, function (Container $container) {
            return $this->getCmsSlotFillerStrategyPlugin();
        });

        return $container;
    }

    /**
     * @return \Spryker\Client\CmsSlotExtension\Dependency\Plugin\CmsSlotFillerStrategyPluginInterface[]
     */
    public function getCmsSlotFillerStrategyPlugin(): array
    {
        return [];
    }
}
