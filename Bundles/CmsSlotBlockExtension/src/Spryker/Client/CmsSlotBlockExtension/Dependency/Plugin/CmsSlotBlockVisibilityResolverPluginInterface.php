<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Generated\Shared\Transfer\CmsSlotParamsTransfer;

interface CmsSlotBlockVisibilityResolverPluginInterface
{
    /**
     * Specification:
     * - Returns true if this plugin is applicable with provided conditions.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer $cmsSlotBlockTransfer
     *
     * @return bool
     */
    public function isApplicable(CmsSlotBlockTransfer $cmsSlotBlockTransfer): bool;

    /**
     * Specification:
     * - Returns true if CMS block should be rendered.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer $cmsSlotBlockTransfer
     * @param \Generated\Shared\Transfer\CmsSlotParamsTransfer $cmsSlotParamsTransfer
     *
     * @return bool
     */
    public function isCmsBlockVisibleInSlot(
        CmsSlotBlockTransfer $cmsSlotBlockTransfer,
        CmsSlotParamsTransfer $cmsSlotParamsTransfer
    ): bool;
}
