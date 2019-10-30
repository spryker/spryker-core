<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlot\Persistence;

use Generated\Shared\Transfer\CmsSlotTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CmsSlot\Persistence\CmsSlotPersistenceFactory getFactory()
 */
class CmsSlotEntityManager extends AbstractEntityManager implements CmsSlotEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotTransfer $cmsSlotTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotTransfer
     */
    public function updateCmsSlot(CmsSlotTransfer $cmsSlotTransfer): CmsSlotTransfer
    {
        $cmsSlotTransfer->requireIdCmsSlot();

        $cmsSlot = $this->getFactory()->createCmsSlotQuery()->findOneByIdCmsSlot($cmsSlotTransfer->getIdCmsSlot());

        $cmsSlotMapper = $this->getFactory()->createCmsSlotMapper();
        $cmsSlot = $cmsSlotMapper->mapCmsSlotTransferToEntity($cmsSlot, $cmsSlotTransfer);
        $cmsSlot->save();

        return $cmsSlotMapper->mapCmsSlotEntityToTransfer($cmsSlot);
    }
}
