<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockGui\Business;

use Generated\Shared\Transfer\CmsBlockCriteriaTransfer;
use Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CmsSlotBlockGui\Business\CmsSlotBlockGuiBusinessFactory getFactory()
 */
class CmsSlotBlockGuiFacade extends AbstractFacade implements CmsSlotBlockGuiFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockCriteriaTransfer $cmsBlockCriteriaTransfer
     * @param \Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
     *
     * @return array
     */
    public function getPaginatedCmsBlocks(
        CmsBlockCriteriaTransfer $cmsBlockCriteriaTransfer,
        CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
    ): array {
        return $this->getFactory()
            ->createCmsBlockSuggestionFinder()
            ->getCmsBlockSuggestions($cmsBlockCriteriaTransfer, $cmsSlotBlockCriteriaTransfer);
    }
}
