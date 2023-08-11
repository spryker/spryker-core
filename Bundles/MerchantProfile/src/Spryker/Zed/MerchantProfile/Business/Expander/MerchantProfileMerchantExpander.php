<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Business\Expander;

use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantProfileCollectionTransfer;
use Generated\Shared\Transfer\MerchantProfileCriteriaTransfer;
use Spryker\Zed\MerchantProfile\Persistence\MerchantProfileRepositoryInterface;

class MerchantProfileMerchantExpander implements MerchantProfileMerchantExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantProfile\Persistence\MerchantProfileRepositoryInterface
     */
    protected MerchantProfileRepositoryInterface $merchantProfileRepository;

    /**
     * @param \Spryker\Zed\MerchantProfile\Persistence\MerchantProfileRepositoryInterface $merchantProfileRepository
     */
    public function __construct(
        MerchantProfileRepositoryInterface $merchantProfileRepository
    ) {
        $this->merchantProfileRepository = $merchantProfileRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function expand(MerchantCollectionTransfer $merchantCollectionTransfer): MerchantCollectionTransfer
    {
        if ($merchantCollectionTransfer->getMerchants()->count() === 0) {
            return $merchantCollectionTransfer;
        }

        $merchantProfileCriteriaTransfer = $this->createMerchantProfileCriteriaTransfer($merchantCollectionTransfer);
        $merchantProfileCollectionTransfer = $this->merchantProfileRepository->get($merchantProfileCriteriaTransfer);

        if ($merchantProfileCollectionTransfer->getMerchantProfiles()->count() === 0) {
            return $merchantCollectionTransfer;
        }

        $merchantProfileTransfersIndexedByIdMerchant = $this->getMerchantProfileTransfersIndexedByIdMerchant($merchantProfileCollectionTransfer);

        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            $idMerchant = $merchantTransfer->getIdMerchant();

            if (!isset($merchantProfileTransfersIndexedByIdMerchant[$idMerchant])) {
                continue;
            }

            $merchantTransfer->setMerchantProfile($merchantProfileTransfersIndexedByIdMerchant[$idMerchant]);
        }

        return $merchantCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileCriteriaTransfer
     */
    protected function createMerchantProfileCriteriaTransfer(
        MerchantCollectionTransfer $merchantCollectionTransfer
    ): MerchantProfileCriteriaTransfer {
        $merchantIds = [];

        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            $merchantIds[] = $merchantTransfer->getIdMerchantOrFail();
        }

        return (new MerchantProfileCriteriaTransfer())
            ->setMerchantIds($merchantIds);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileCollectionTransfer $merchantProfileCollectionTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\MerchantProfileTransfer>
     */
    protected function getMerchantProfileTransfersIndexedByIdMerchant(
        MerchantProfileCollectionTransfer $merchantProfileCollectionTransfer
    ): array {
        $merchantProfileTransfersIndexedByIdMerchant = [];

        foreach ($merchantProfileCollectionTransfer->getMerchantProfiles() as $merchantProfileTransfer) {
            $fkMerchant = $merchantProfileTransfer->getFkMerchantOrFail();
            $merchantProfileTransfersIndexedByIdMerchant[$fkMerchant] = $merchantProfileTransfer;
        }

        return $merchantProfileTransfersIndexedByIdMerchant;
    }
}
