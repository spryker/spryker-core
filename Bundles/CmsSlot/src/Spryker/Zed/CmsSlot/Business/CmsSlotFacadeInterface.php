<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlot\Business;

use Generated\Shared\Transfer\CmsSlotTemplateTransfer;
use Generated\Shared\Transfer\CmsSlotTransfer;
use Generated\Shared\Transfer\DataObjectValidationResponseTransfer;

interface CmsSlotFacadeInterface
{
    /**
     * Specification:
     * - Validates cms slot transfer.
     * - Returns DataObjectValidationResponseTransfer with status and messages in case of fail.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsSlotTransfer $cmsSlotTransfer
     *
     * @return \Generated\Shared\Transfer\DataObjectValidationResponseTransfer
     */
    public function validateCmsSlot(CmsSlotTransfer $cmsSlotTransfer): DataObjectValidationResponseTransfer;

    /**
     * Specification:
     * - Validates cms slot template transfer.
     * - Returns DataObjectValidationResponseTransfer with status and messages in case of fail.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsSlotTemplateTransfer $cmsSlotTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\DataObjectValidationResponseTransfer
     */
    public function validateCmsSlotTemplate(CmsSlotTemplateTransfer $cmsSlotTemplateTransfer): DataObjectValidationResponseTransfer;
}
