<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CmsSlotStorage\Persistence\CmsSlotStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\CmsSlotStorage\Business\CmsSlotStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\CmsSlotStorage\Persistence\CmsSlotStorageEntityManagerInterface getEntityManager()
 */
class CmsSlotStorageFacade extends AbstractFacade implements CmsSlotStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $cmsSlotIds
     *
     * @return void
     */
    public function publishCmsSlots(array $cmsSlotIds): void
    {
        $this->getFactory()->createCmsSlotStoragePublisher()->publish($cmsSlotIds);
    }
}
