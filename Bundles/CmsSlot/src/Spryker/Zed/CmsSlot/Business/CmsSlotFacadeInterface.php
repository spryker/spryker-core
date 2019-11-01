<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlot\Business;

use Generated\Shared\Transfer\CmsSlotCriteriaFilterTransfer;
use Generated\Shared\Transfer\CmsSlotTemplateTransfer;
use Generated\Shared\Transfer\CmsSlotTransfer;
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
     * - Retrieves CMS slots by criteria filter.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsSlotCriteriaFilterTransfer $cmsSlotCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotTransfer[]
     */
    public function getCmsSlotsByCriteriaFilter(CmsSlotCriteriaFilterTransfer $cmsSlotCriteriaFilterTransfer): array;
}
