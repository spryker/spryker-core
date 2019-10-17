<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlot\Business;

use Generated\Shared\Transfer\CmsSlotTemplateTransfer;
use Generated\Shared\Transfer\CmsSlotTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;

interface CmsSlotFacadeInterface
{
    /**
     * Specification:
     * - Validates cms slot transfer.
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
     * - Validates cms slot template transfer.
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
     * - Activates cms slot by id.
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
     * - Deactivates cms slot by id.
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
     * - Retrieves CMS slot according to given CMS slot id.
     * - Returns NULL if  CMS slot does not exist.
     *
     * @api
     *
     * @param int $idCmsSlot
     *
     * @return \Generated\Shared\Transfer\CmsSlotTransfer|null
     */
    public function findCmsSlotById(int $idCmsSlot): ?CmsSlotTransfer;
}
