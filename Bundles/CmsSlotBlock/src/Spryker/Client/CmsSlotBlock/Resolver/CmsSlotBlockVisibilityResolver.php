<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlock\Resolver;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Client\CmsSlotBlock\CmsSlotBlockConfig;

class CmsSlotBlockVisibilityResolver implements CmsSlotBlockVisibilityResolverInterface
{
    /**
     * @var \Spryker\Client\CmsSlotBlock\CmsSlotBlockConfig
     */
    protected $cmsSlotBlockConfig;

    /**
     * @var \Spryker\Client\CmsSlotBlockExtension\Dependency\Plugin\CmsSlotBlockVisibilityResolverPluginInterface[]
     */
    protected $cmsSlotBlockVisibilityResolverPlugins;

    /**
     * @param \Spryker\Client\CmsSlotBlock\CmsSlotBlockConfig $cmsSlotBlockConfig
     * @param \Spryker\Client\CmsSlotBlockExtension\Dependency\Plugin\CmsSlotBlockVisibilityResolverPluginInterface[] $cmsSlotBlockVisibilityResolverPlugins
     */
    public function __construct(
        CmsSlotBlockConfig $cmsSlotBlockConfig,
        array $cmsSlotBlockVisibilityResolverPlugins
    ) {
        $this->cmsSlotBlockVisibilityResolverPlugins = $cmsSlotBlockVisibilityResolverPlugins;
        $this->cmsSlotBlockConfig = $cmsSlotBlockConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     * @param array $cmsSlotParams
     *
     * @return bool
     */
    public function isCmsBlockVisibleInSlot(CmsBlockTransfer $cmsBlockTransfer, array $cmsSlotParams): bool
    {
        $cmsBlockTransfer->requireCmsSlotBlockConditions();

        $cmsSlotBlockVisibilityResolverPlugins = $this->getApplicablePlugins($cmsBlockTransfer);

        if (!$cmsSlotBlockVisibilityResolverPlugins) {
            return $this->cmsSlotBlockConfig->getIsCmsBlockVisibleInSlotByDefault();
        }

        foreach ($cmsSlotBlockVisibilityResolverPlugins as $cmsSlotBlockVisibilityResolverPlugin) {
            if (!$cmsSlotBlockVisibilityResolverPlugin->isCmsBlockVisibleInSlot($cmsBlockTransfer, $cmsSlotParams)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return \Spryker\Client\CmsSlotBlockExtension\Dependency\Plugin\CmsSlotBlockVisibilityResolverPluginInterface[]
     */
    protected function getApplicablePlugins(CmsBlockTransfer $cmsBlockTransfer): array
    {
        $applicablePlugins = [];
        foreach ($this->cmsSlotBlockVisibilityResolverPlugins as $cmsSlotBlockVisibilityResolverPlugin) {
            if ($cmsSlotBlockVisibilityResolverPlugin->isApplicable($cmsBlockTransfer)) {
                $applicablePlugins[] = $cmsSlotBlockVisibilityResolverPlugin;
            }
        }

        return $applicablePlugins;
    }
}
