<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CmsBlockTransfer;

interface CmsSlotBlockVisibilityResolverPluginInterface
{
    /**
     * Specification:
     * - Returns true if this plugin is applicable with provided conditions.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return bool
     */
    public function isApplicable(CmsBlockTransfer $cmsBlockTransfer): bool;

    /**
     * Specification:
     * - Returns true if CMS block should be rendered.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     * @param array $cmsSlotData
     *
     * @return bool
     */
    public function isCmsBlockVisibleInSlot(CmsBlockTransfer $cmsBlockTransfer, array $cmsSlotData): bool;
}
