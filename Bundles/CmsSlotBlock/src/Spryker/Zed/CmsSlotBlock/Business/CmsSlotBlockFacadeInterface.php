<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Business;

use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;

interface CmsSlotBlockFacadeInterface
{
    /**
     * Specification:
     * - Saves relations between CMS Slots and CMS blocks.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer
     *
     * @return void
     */
    public function saveCmsSlotBlockRelations(CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer): void;

    /**
     * Specification:
     * - Retrieves collection of CmsSlotBlockTransfers according to given CMS slot template id and CMS slot id.
     *
     * @api
     *
     * @param int $idCmsSlotTemplate
     * @param int $idCmsSlot
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer
     */
    public function getCmsSlotBlockCollection(int $idCmsSlotTemplate, int $idCmsSlot): CmsSlotBlockCollectionTransfer;
}
