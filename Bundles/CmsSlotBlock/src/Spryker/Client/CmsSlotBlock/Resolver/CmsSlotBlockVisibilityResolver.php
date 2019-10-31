<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Client\CmsSlotBlock\Resolver;


use Generated\Shared\Transfer\CmsBlockTransfer;

class CmsSlotBlockVisibilityResolver implements CmsSlotBlockVisibilityResolverInterface
{
    /**
     * @var array|\Spryker\Client\CmsSlotBlockExtension\Dependency\Plugin\CmsSlotBlockVisibilityResolverPluginInterface[]
     */
    protected $cmsSlotBlockVisibilityResolverPlugins;

    /**
     * @param \Spryker\Client\CmsSlotBlockExtension\Dependency\Plugin\CmsSlotBlockVisibilityResolverPluginInterface[] $cmsSlotBlockVisibilityResolverPlugins
     */
    public function __construct(array $cmsSlotBlockVisibilityResolverPlugins)
    {
        $this->cmsSlotBlockVisibilityResolverPlugins = $cmsSlotBlockVisibilityResolverPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     * @param array $conditions
     * @param array $cmsSlotData
     *
     * @return bool
     */
    public function isCmsBlockVisibleInSlot(
        CmsBlockTransfer $cmsBlockTransfer,
        array $conditions,
        array $cmsSlotData
    ): bool {

    }
}