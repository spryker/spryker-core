<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Business;

use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CmsSlotBlock\Business\CmsSlotBlockBusinessFactory getFactory()
 */
class CmsSlotBlockFacade extends AbstractFacade implements CmsSlotBlockFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer
     *
     * @return void
     */
    public function saveCmsSlotBlockRelations(CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer): void
    {
        $this->getFactory()
            ->createCmsSlotBlockRelationsWriter()
            ->saveCmsSlotBlockRelations($cmsSlotBlockCollectionTransfer);
    }
}
