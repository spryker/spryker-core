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
    public const CMS_SLOT_EXTERNAL_DATA_PROVIDER_STRATEGY_PLUGINS = 'CMS_SLOT_EXTERNAL_DATA_PROVIDER_STRATEGY_PLUGINS';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = $this->addCmsSlotExternalDataProviderStrategyPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCmsSlotExternalDataProviderStrategyPlugin(Container $container): Container
    {
        $container->set(static::CMS_SLOT_EXTERNAL_DATA_PROVIDER_STRATEGY_PLUGINS, function (Container $container) {
            return $this->getCmsSlotExternalDataProviderStrategyPlugin();
        });

        return $container;
    }

    /**
     * @return \Spryker\Client\CmsSlotExtension\Dependency\Plugin\CmsSlotExternalDataProviderStrategyPluginInterface[]
     */
    public function getCmsSlotExternalDataProviderStrategyPlugin(): array
    {
        return [];
    }
}
