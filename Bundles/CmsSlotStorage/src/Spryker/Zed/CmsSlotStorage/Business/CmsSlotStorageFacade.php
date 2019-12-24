<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;
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
        $this->getFactory()->createCmsSlotStoragePublisher()->publishByCmsSlotIds($cmsSlotIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $cmsSlotStorageIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getSynchronizationTransferCollection(
        FilterTransfer $filterTransfer,
        array $cmsSlotStorageIds
    ): array {
        return $this->getFactory()
            ->createCmsSlotStorageReader()
            ->getSynchronizationTransferCollection($filterTransfer, $cmsSlotStorageIds);
    }
}
