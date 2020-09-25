<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockGui\Business;

use Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;

/**
 * @method \Spryker\Zed\CmsSlotBlockGui\Business\CmsSlotBlockGuiBusinessFactory getFactory()
 */
interface CmsSlotBlockGuiFacadeInterface
{
    /**
     * Specification:
     * - Searches CMS Blocks suggestions based on CMS Block name and pagination.
     * - Transform CMS Blocks transfers collection to suggestion data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return array
     */
    public function getCmsBlockSuggestions(
        CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer,
        PaginationTransfer $paginationTransfer
    ): array;
}
