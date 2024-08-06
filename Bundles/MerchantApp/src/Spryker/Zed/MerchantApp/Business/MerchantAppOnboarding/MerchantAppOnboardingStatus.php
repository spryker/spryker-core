<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp\Business\MerchantAppOnboarding;

use Generated\Shared\Transfer\MerchantAppOnboardingCriteriaTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingStatusTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\MerchantApp\Persistence\MerchantAppEntityManagerInterface;
use Spryker\Zed\MerchantApp\Persistence\MerchantAppRepositoryInterface;

class MerchantAppOnboardingStatus implements MerchantAppOnboardingStatusInterface
{
    /**
     * @var \Spryker\Zed\MerchantApp\Persistence\MerchantAppEntityManagerInterface
     */
    protected MerchantAppEntityManagerInterface $merchantAppEntityManager;

    /**
     * @var \Spryker\Zed\MerchantApp\Persistence\MerchantAppRepositoryInterface
     */
    protected MerchantAppRepositoryInterface $merchantAppRepository;

    /**
     * @param \Spryker\Zed\MerchantApp\Persistence\MerchantAppEntityManagerInterface $merchantAppEntityManager
     * @param \Spryker\Zed\MerchantApp\Persistence\MerchantAppRepositoryInterface $merchantAppRepository
     */
    public function __construct(MerchantAppEntityManagerInterface $merchantAppEntityManager, MerchantAppRepositoryInterface $merchantAppRepository)
    {
        $this->merchantAppEntityManager = $merchantAppEntityManager;
        $this->merchantAppRepository = $merchantAppRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingStatusTransfer $merchantAppOnboardingStatusTransfer
     *
     * @return void
     */
    public function updateMerchantAppOnboardingStatus(MerchantAppOnboardingStatusTransfer $merchantAppOnboardingStatusTransfer): void
    {
        $merchantAppOnboardingStatusCriteria = new MerchantAppOnboardingCriteriaTransfer();
        $merchantAppOnboardingStatusCriteria
            ->setMerchant((new MerchantTransfer())->setMerchantReference($merchantAppOnboardingStatusTransfer->getMerchantReference()));

        $merchantAppOnboardingStatusCollectionTransfer = $this->merchantAppRepository->getMerchantAppOnboardingStatusCollection($merchantAppOnboardingStatusCriteria);

        if ($merchantAppOnboardingStatusCollectionTransfer->getStatuses()->count() === 0) {
            return;
        }

        $persistedMerchantAppOnboardingStatusTransfer = $merchantAppOnboardingStatusCollectionTransfer->getStatuses()->getIterator()->current();
        $persistedMerchantAppOnboardingStatusTransfer->setStatus($merchantAppOnboardingStatusTransfer->getStatus());

        $this->merchantAppEntityManager->persistAppMerchantAppOnboardingStatus($persistedMerchantAppOnboardingStatusTransfer);
    }
}
