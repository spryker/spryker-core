<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlot\Business;

use Generated\Shared\Transfer\CmsSlotTemplateTransfer;
use Generated\Shared\Transfer\CmsSlotTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CmsSlot\Business\CmsSlotBusinessFactory getFactory()
 * @method \Spryker\Zed\CmsSlot\Persistence\CmsSlotRepositoryInterface getRepository()
 * @method \Spryker\Zed\CmsSlot\Persistence\CmsSlotEntityManagerInterface getEntityManager()
 */
class CmsSlotFacade extends AbstractFacade implements CmsSlotFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsSlotTransfer $cmsSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validateCmsSlot(CmsSlotTransfer $cmsSlotTransfer): ValidationResponseTransfer
    {
        return $this->getFactory()->createCmsSlotValidator()->validateCmsSlot($cmsSlotTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsSlotTemplateTransfer $cmsSlotTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validateCmsSlotTemplate(CmsSlotTemplateTransfer $cmsSlotTemplateTransfer): ValidationResponseTransfer
    {
        return $this->getFactory()->createCmsSlotTemplateValidator()->validateCmsSlotTemplate($cmsSlotTemplateTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCmsSlot
     *
     * @return void
     */
    public function activateByIdCmsSlot(int $idCmsSlot): void
    {
        $this->getFactory()->createCmsSlotActivator()->activateByIdCmsSlot($idCmsSlot);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCmsSlot
     *
     * @return void
     */
    public function deactivateByIdCmsSlot(int $idCmsSlot): void
    {
        $this->getFactory()->createCmsSlotActivator()->deactivateByIdCmsSlot($idCmsSlot);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCmsSlot
     *
     * @return \Generated\Shared\Transfer\CmsSlotTransfer|null
     */
    public function findCmsSlotById(int $idCmsSlot): ?CmsSlotTransfer
    {
        return $this->getRepository()->findCmsSlotById($idCmsSlot);
    }
}
