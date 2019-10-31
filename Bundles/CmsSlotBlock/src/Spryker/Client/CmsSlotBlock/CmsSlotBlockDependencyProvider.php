<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Client\CmsSlotBlock;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

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
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addCmsSlotBlockVisibilityResolverPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCmsSlotBlockVisibilityResolverPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CMS_SLOT_BLOCK_VISIBILITY_RESOLVER, function (Container $container) {
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