<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Client\CmsSlotBlock;

use Spryker\Client\CmsSlotBlock\Resolver\CmsSlotBlockVisibilityResolver;
use Spryker\Client\CmsSlotBlock\Resolver\CmsSlotBlockVisibilityResolverInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CmsSlotBlockFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CmsSlotBlock\Resolver\CmsSlotBlockVisibilityResolverInterface
     */
    public function createCmsSlotBlockVisibilityResolver(): CmsSlotBlockVisibilityResolverInterface
    {
        return new CmsSlotBlockVisibilityResolver($this->getCmsSlotBlockVisibilityResolverPlugins());
    }

    /**
     * @return \Spryker\Client\CmsSlotBlockExtension\Dependency\Plugin\CmsSlotBlockVisibilityResolverPluginInterface[]
     */
    public function getCmsSlotBlockVisibilityResolverPlugins(): array
    {
        return $this->getProvidedDependency(CmsSlotBlockDependencyProvider::PLUGINS_CMS_SLOT_BLOCK_VISIBILITY_RESOLVER);
    }
}
