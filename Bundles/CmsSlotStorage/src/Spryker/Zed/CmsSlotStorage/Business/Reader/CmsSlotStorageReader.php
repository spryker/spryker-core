<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotStorage\Business\Reader;

use Generated\Shared\Transfer\CmsSlotCriteriaTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Zed\CmsSlotStorage\Persistence\CmsSlotStorageRepositoryInterface;

class CmsSlotStorageReader implements CmsSlotStorageReaderInterface
{
    /**
     * @var \Spryker\Zed\CmsSlotStorage\Persistence\CmsSlotStorageRepositoryInterface
     */
    protected $cmsSlotStorageRepository;

    /**
     * @param \Spryker\Zed\CmsSlotStorage\Persistence\CmsSlotStorageRepositoryInterface $cmsSlotStorageRepository
     */
    public function __construct(CmsSlotStorageRepositoryInterface $cmsSlotStorageRepository)
    {
        $this->cmsSlotStorageRepository = $cmsSlotStorageRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotCriteriaTransfer $cmsSlotCriteriaTransfer
     * @param int[] $cmsSlotStorageIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getSynchronizationTransferCollection(
        CmsSlotCriteriaTransfer $cmsSlotCriteriaTransfer,
        array $cmsSlotStorageIds
    ): array {
        $synchronizationDataTransfers = [];

        $cmsSlotStorageEntityTransfers = $this->cmsSlotStorageRepository->getFilteredCmsSlotStorageEntities(
            $cmsSlotCriteriaTransfer->getFilter(),
            $cmsSlotStorageIds
        );

        foreach ($cmsSlotStorageEntityTransfers as $cmsSlotStorageEntityTransfer) {
            $synchronizationDataTransfer = new SynchronizationDataTransfer();
            $synchronizationDataTransfer->setData($cmsSlotStorageEntityTransfer->getData());
            $synchronizationDataTransfer->setKey($cmsSlotStorageEntityTransfer->getKey());

            $synchronizationDataTransfers[] = $synchronizationDataTransfer;
        }

        return $synchronizationDataTransfers;
    }
}
