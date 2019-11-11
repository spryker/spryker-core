<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockProductCategoryConnector;

use Generated\Shared\Transfer\CmsBlockTransfer;

interface CmsSlotBlockProductCategoryConnectorClientInterface
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
    public function resolveIsSlotBlockConditionApplicable(CmsBlockTransfer $cmsBlockTransfer): bool;

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
    public function resolveIsCmsBlockVisibleInSlot(CmsBlockTransfer $cmsBlockTransfer, array $cmsSlotData): bool;
}
