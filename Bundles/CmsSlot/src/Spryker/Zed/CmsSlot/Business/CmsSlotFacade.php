<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlot\Business;

use Generated\Shared\Transfer\CmsSlotTemplateTransfer;
use Generated\Shared\Transfer\CmsSlotTransfer;
use Generated\Shared\Transfer\DataObjectValidationResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CmsSlot\Business\CmsSlotBusinessFactory getFactory()
 */
class CmsSlotFacade extends AbstractFacade implements CmsSlotFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsSlotTransfer $cmsSlotTransfer
     *
     * @return \Generated\Shared\Transfer\DataObjectValidationResponseTransfer
     */
    public function validateCmsSlot(CmsSlotTransfer $cmsSlotTransfer): DataObjectValidationResponseTransfer
    {
        return $this->getFactory()->createCmsSlotValidator()->validateCmsSlot($cmsSlotTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsSlotTemplateTransfer $cmsSlotTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\DataObjectValidationResponseTransfer
     */
    public function validateCmsSlotTemplate(CmsSlotTemplateTransfer $cmsSlotTemplateTransfer): DataObjectValidationResponseTransfer
    {
        return $this->getFactory()->createCmsSlotTemplateValidator()->validateCmsSlotTemplate($cmsSlotTemplateTransfer);
    }
}
