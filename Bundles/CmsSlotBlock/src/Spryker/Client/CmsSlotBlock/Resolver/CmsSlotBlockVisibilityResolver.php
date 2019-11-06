<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
        foreach ($this->cmsSlotBlockVisibilityResolverPlugins as $cmsSlotBlockVisibilityResolverPlugin) {
            if (!$cmsSlotBlockVisibilityResolverPlugin->isApplicable($conditions)) {
                continue;
            }

            return $cmsSlotBlockVisibilityResolverPlugin->isCmsBlockVisibleInSlot($cmsBlockTransfer, $conditions, $cmsSlotData);
        }

        return $this->getIsCmsBlockVisibleInSlotDefault();
    }

    /**
     * @return bool
     */
    protected function getIsCmsBlockVisibleInSlotDefault(): bool
    {
        return true;
    }
}
