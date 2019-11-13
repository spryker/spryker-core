<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockCategoryConnector;

use Generated\Shared\Transfer\CmsBlockTransfer;

interface CmsSlotBlockCategoryConnectorClientInterface
{
    /**
     * Specification:
     * - Returns true if CMS slot block condition is applicable.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return bool
     */
    public function isSlotBlockConditionApplicable(CmsBlockTransfer $cmsBlockTransfer): bool;

    /**
     * Specification:
     * - Returns true if CMS block should be visible in Slot.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     * @param array $cmsSlotParams
     *
     * @return bool
     */
    public function isCmsBlockVisibleInSlot(CmsBlockTransfer $cmsBlockTransfer, array $cmsSlotParams): bool;
}
