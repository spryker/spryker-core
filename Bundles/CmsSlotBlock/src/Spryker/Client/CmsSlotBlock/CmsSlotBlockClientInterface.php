<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlock;

use Generated\Shared\Transfer\CmsBlockTransfer;

interface CmsSlotBlockClientInterface
{
    /**
     * Specification:
     * - Checks if CMS block should be rendered.
     * - Executes CmsSlotBlockVisibilityResolverPluginInterface plugins.
     *
     * @api
     *
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
    ): bool;
}
