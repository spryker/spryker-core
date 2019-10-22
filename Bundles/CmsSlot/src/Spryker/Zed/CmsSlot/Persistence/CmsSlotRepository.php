<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlot\Persistence;

use Generated\Shared\Transfer\CmsSlotTemplateTransfer;
use Generated\Shared\Transfer\CmsSlotTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CmsSlot\Persistence\CmsSlotPersistenceFactory getFactory()
 */
class CmsSlotRepository extends AbstractRepository implements CmsSlotRepositoryInterface
{
    /**
     * @param int $idCmsSlot
     *
     * @return \Generated\Shared\Transfer\CmsSlotTransfer|null
     */
    public function findCmsSlotById(int $idCmsSlot): ?CmsSlotTransfer
    {
        $cmsSlot = $this->getFactory()->createCmsSlotQuery()->findOneByIdCmsSlot($idCmsSlot);

        if (!$cmsSlot) {
            return null;
        }

        return $this->getFactory()->createCmsSlotMapper()->mapCmsSlotEntityToTransfer($cmsSlot);
    }

    /**
     * @param int $idCmsSlotTemplate
     *
     * @return \Generated\Shared\Transfer\CmsSlotTemplateTransfer|null
     */
    public function findCmsSlotTemplateById(int $idCmsSlotTemplate): ?CmsSlotTemplateTransfer
    {
        $cmsSlotTemplate = $this->getFactory()->createCmsSlotTemplateQuery()->findOneByIdCmsSlotTemplate($idCmsSlotTemplate);

        if (!$cmsSlotTemplate) {
            return null;
        }

        return $this->getFactory()->createCmsSlotMapper()->mapCmsSlotTemplateEntityToTransfer($cmsSlotTemplate);
    }
}
