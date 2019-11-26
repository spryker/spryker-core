<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlock\Resolver;

use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Generated\Shared\Transfer\CmsSlotParamsTransfer;
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
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer $cmsSlotBlockTransfer
     * @param \Generated\Shared\Transfer\CmsSlotParamsTransfer $cmsSlotParamsTransfer
     *
     * @return bool
     */
    public function isCmsBlockVisibleInSlot(
        CmsSlotBlockTransfer $cmsSlotBlockTransfer,
        CmsSlotParamsTransfer $cmsSlotParamsTransfer
    ): bool {
        $cmsSlotBlockVisibilityResolverPlugins = $this->getApplicablePlugins($cmsSlotBlockTransfer);

        if (!$cmsSlotBlockVisibilityResolverPlugins) {
            return $this->cmsSlotBlockConfig->getIsCmsBlockVisibleInSlotByDefault();
        }

        foreach ($cmsSlotBlockVisibilityResolverPlugins as $cmsSlotBlockVisibilityResolverPlugin) {
            if (!$cmsSlotBlockVisibilityResolverPlugin->isCmsBlockVisibleInSlot($cmsSlotBlockTransfer, $cmsSlotParamsTransfer)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer $cmsSlotBlockTransfer
     *
     * @return \Spryker\Client\CmsSlotBlockExtension\Dependency\Plugin\CmsSlotBlockVisibilityResolverPluginInterface[]
     */
    protected function getApplicablePlugins(CmsSlotBlockTransfer $cmsSlotBlockTransfer): array
    {
        $applicablePlugins = [];
        foreach ($this->cmsSlotBlockVisibilityResolverPlugins as $cmsSlotBlockVisibilityResolverPlugin) {
            if ($cmsSlotBlockVisibilityResolverPlugin->isApplicable($cmsSlotBlockTransfer)) {
                $applicablePlugins[] = $cmsSlotBlockVisibilityResolverPlugin;
            }
        }

        return $applicablePlugins;
    }
}
