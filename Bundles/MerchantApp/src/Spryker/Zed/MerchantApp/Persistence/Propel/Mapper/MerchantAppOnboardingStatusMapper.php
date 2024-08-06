<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantAppOnboardingStatusCollectionTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingStatusTransfer;
use Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboardingStatus;
use Propel\Runtime\Collection\Collection;

class MerchantAppOnboardingStatusMapper
{
    /**
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingStatusTransfer $merchantAppOnboardingStatusTransfer
     * @param \Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboardingStatus $merchantAppOnboardingStatusEntity
     *
     * @return \Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboardingStatus
     */
    public function mapMerchantAppOnboardingStatusTransferToMerchantAppOnboardingStatusEntity(
        MerchantAppOnboardingStatusTransfer $merchantAppOnboardingStatusTransfer,
        SpyMerchantAppOnboardingStatus $merchantAppOnboardingStatusEntity
    ): SpyMerchantAppOnboardingStatus {
        $merchantAppOnboardingStatusEntity->fromArray(
            $merchantAppOnboardingStatusTransfer->modifiedToArray(),
        );

        if (!$merchantAppOnboardingStatusEntity->getFkMerchantAppOnboarding()) {
            $merchantAppOnboardingStatusEntity->setFkMerchantAppOnboarding(
                $merchantAppOnboardingStatusTransfer->getMerchantAppOnboardingOrFail()->getIdMerchantAppOnboardingOrFail(),
            );
        }
        $merchantAppOnboardingStatusEntity->setStatus($merchantAppOnboardingStatusTransfer->getStatusOrFail());

        return $merchantAppOnboardingStatusEntity;
    }

    /**
     * @param \Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboardingStatus $merchantAppOnboardingStatusEntity
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingStatusTransfer $merchantAppOnboardingStatusTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAppOnboardingStatusTransfer
     */
    public function mapMerchantAppOnboardingStatusEntityToMerchantAppOnboardingStatusTransfer(
        SpyMerchantAppOnboardingStatus $merchantAppOnboardingStatusEntity,
        MerchantAppOnboardingStatusTransfer $merchantAppOnboardingStatusTransfer
    ): MerchantAppOnboardingStatusTransfer {
        $merchantAppOnboardingStatusTransfer->fromArray(
            $merchantAppOnboardingStatusEntity->toArray(),
            true,
        );

        return $merchantAppOnboardingStatusTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection $merchantAppOnboardingStatusEntityCollection
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingStatusCollectionTransfer $merchantAppOnboardingStatusTransferCollection
     *
     * @return \Generated\Shared\Transfer\MerchantAppOnboardingStatusCollectionTransfer
     */
    public function mapMerchantAppOnboardingEntityCollectionToMerchantAppOnboardingTransferCollection(
        Collection $merchantAppOnboardingStatusEntityCollection,
        MerchantAppOnboardingStatusCollectionTransfer $merchantAppOnboardingStatusTransferCollection
    ): MerchantAppOnboardingStatusCollectionTransfer {
        foreach ($merchantAppOnboardingStatusEntityCollection as $merchantAppOnboardingStatusEntity) {
            $merchantAppOnboardingStatusTransferCollection->addStatus(
                $this->mapMerchantAppOnboardingStatusEntityToMerchantAppOnboardingStatusTransfer($merchantAppOnboardingStatusEntity, new MerchantAppOnboardingStatusTransfer()),
            );
        }

        return $merchantAppOnboardingStatusTransferCollection;
    }
}
