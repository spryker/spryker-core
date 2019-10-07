<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlot\Business;

use Generated\Shared\Transfer\CmsSlotTemplateTransfer;
use Generated\Shared\Transfer\CmsSlotTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;

interface CmsSlotFacadeInterface
{
    /**
     * Specification:
     * - Validates CMS slot transfer.
     * - Returns ValidationResponseTransfer with status and messages in case of fail.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsSlotTransfer $cmsSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validateCmsSlot(CmsSlotTransfer $cmsSlotTransfer): ValidationResponseTransfer;

    /**
     * Specification:
     * - Validates CMS slot template transfer.
     * - Returns ValidationResponseTransfer with status and messages in case of fail.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsSlotTemplateTransfer $cmsSlotTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validateCmsSlotTemplate(CmsSlotTemplateTransfer $cmsSlotTemplateTransfer): ValidationResponseTransfer;

    /**
     * Specification:
     * - Activates CMS slot by id.
     *
     * @api
     *
     * @param int $idCmsSlot
     *
     * @return void
     */
    public function activateByIdCmsSlot(int $idCmsSlot): void;

    /**
     * Specification:
     * - Deactivates CMS slot by id.
     *
     * @api
     *
     * @param int $idCmsSlot
     *
     * @return void
     */
    public function deactivateByIdCmsSlot(int $idCmsSlot): void;

    /**
     * Specification:
     * - Retrieves CMS slots according to given offset and limit.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotTransfer[]
     */
    public function getFilteredCmsSlots(FilterTransfer $filterTransfer): array;

    /**
     * Specification:
     * - Retrieves CMS slots according to given CMS slot ids.
     *
     * @api
     *
     * @param int[] $cmsSlotIds
     *
     * @return \Generated\Shared\Transfer\CmsSlotTransfer[]
     */
    public function getCmsSlotsByCmsSlotIds(array $cmsSlotIds): array;
}
