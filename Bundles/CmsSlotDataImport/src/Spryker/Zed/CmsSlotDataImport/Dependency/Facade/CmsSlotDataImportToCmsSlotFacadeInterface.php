<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CmsSlotDataImport\Dependency\Facade;

use Generated\Shared\Transfer\CmsSlotTemplateTransfer;
use Generated\Shared\Transfer\CmsSlotTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;

interface CmsSlotDataImportToCmsSlotFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotTransfer $cmsSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validateCmsSlot(CmsSlotTransfer $cmsSlotTransfer): ValidationResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\CmsSlotTemplateTransfer $cmsSlotTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validateCmsSlotTemplate(CmsSlotTemplateTransfer $cmsSlotTemplateTransfer): ValidationResponseTransfer;
}
