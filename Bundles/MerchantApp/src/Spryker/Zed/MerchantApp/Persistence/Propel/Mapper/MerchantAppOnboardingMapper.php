<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantAppOnboardingCollectionTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingTransfer;
use Generated\Shared\Transfer\OnboardingTransfer;
use Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboarding;
use Propel\Runtime\Collection\Collection;

class MerchantAppOnboardingMapper
{
    /**
     * @var \Spryker\Zed\MerchantApp\Persistence\Propel\Mapper\MerchantAppOnboardingStatusMapper
     */
    protected MerchantAppOnboardingStatusMapper $merchantAppOnboardingStatusMapper;

    /**
     * @param \Spryker\Zed\MerchantApp\Persistence\Propel\Mapper\MerchantAppOnboardingStatusMapper $merchantAppOnboardingStatusMapper
     */
    public function __construct(MerchantAppOnboardingStatusMapper $merchantAppOnboardingStatusMapper)
    {
        $this->merchantAppOnboardingStatusMapper = $merchantAppOnboardingStatusMapper;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboarding> $merchantAppOnboardingEntityCollection
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingCollectionTransfer $merchantAppOnboardingDetailsCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAppOnboardingCollectionTransfer
     */
    public function mapMerchantAppOnboardingEntityCollectionToMerchantAppOnboardingCollectionTransfer(
        Collection $merchantAppOnboardingEntityCollection,
        MerchantAppOnboardingCollectionTransfer $merchantAppOnboardingDetailsCollectionTransfer
    ): MerchantAppOnboardingCollectionTransfer {
        foreach ($merchantAppOnboardingEntityCollection as $merchantAppOnboardingEntity) {
            $merchantAppOnboardingDetailsCollectionTransfer->addOnboarding(
                $this->mapMerchantAppOnboardingEntityToMerchantAppOnboardingTransfer(
                    $merchantAppOnboardingEntity,
                    new MerchantAppOnboardingTransfer(),
                ),
            );
        }

        return $merchantAppOnboardingDetailsCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboarding $merchantAppOnboardingEntity
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingTransfer $merchantAppOnboardingTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAppOnboardingTransfer
     */
    protected function mapMerchantAppOnboardingEntityToMerchantAppOnboardingTransfer(
        SpyMerchantAppOnboarding $merchantAppOnboardingEntity,
        MerchantAppOnboardingTransfer $merchantAppOnboardingTransfer
    ): MerchantAppOnboardingTransfer {
        $merchantAppOnboardingData = $merchantAppOnboardingEntity->toArray();
        $merchantAppOnboardingData['additional_content'] = json_decode($merchantAppOnboardingData['additional_content'], true) ?? null;
        $merchantAppOnboardingTransfer->fromArray($merchantAppOnboardingData, true);

        $onboardingTransfer = (new OnboardingTransfer())
            ->setUrl($merchantAppOnboardingEntity->getOnboardingUrl())
            ->setStrategy($merchantAppOnboardingEntity->getOnboardingStrategy());

        $merchantAppOnboardingTransfer->setOnboarding($onboardingTransfer);

        return $merchantAppOnboardingTransfer;
    }
}
