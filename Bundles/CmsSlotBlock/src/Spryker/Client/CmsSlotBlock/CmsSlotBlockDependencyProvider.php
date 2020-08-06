<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlock;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

/**
 * @method \Spryker\Client\CmsSlotBlock\CmsSlotBlockConfig getConfig()
 */
class CmsSlotBlockDependencyProvider extends AbstractDependencyProvider
{
    public const PLUGINS_CMS_SLOT_BLOCK_VISIBILITY_RESOLVER = 'PLUGINS_CMS_SLOT_BLOCK_VISIBILITY_RESOLVER';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = $this->addCmsSlotBlockVisibilityResolverPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCmsSlotBlockVisibilityResolverPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CMS_SLOT_BLOCK_VISIBILITY_RESOLVER, function () {
            return $this->getCmsSlotBlockVisibilityResolverPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Client\CmsSlotBlockExtension\Dependency\Plugin\CmsSlotBlockVisibilityResolverPluginInterface[]
     */
    protected function getCmsSlotBlockVisibilityResolverPlugins(): array
    {
        return [];
    }
}
