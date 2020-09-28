<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockGui\Business;

use Generated\Shared\Transfer\CmsBlockCriteriaTransfer;
use Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer;

/**
 * @method \Spryker\Zed\CmsSlotBlockGui\Business\CmsSlotBlockGuiBusinessFactory getFactory()
 */
interface CmsSlotBlockGuiFacadeInterface
{
    /**
     * Specification:
     * - Searches for CMS Blocks suggestions based on criteria and pagination.
     * - Transform CMS Blocks transfer collection to suggestion data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockCriteriaTransfer $cmsBlockCriteriaTransfer
     * @param \Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
     *
     * @return array
     */
    public function getCmsBlockSuggestions(
        CmsBlockCriteriaTransfer $cmsBlockCriteriaTransfer,
        CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
    ): array;
}
