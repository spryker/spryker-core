<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business\Expander;

use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use SprykerFeature\Zed\SspAssetManagement\Business\SspAssetManagementFacadeInterface;
use SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementRepositoryInterface;

class SspAssetSspInquiryExpander implements SspInquiryExpanderInterface
{
    /**
     * @param \SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementRepositoryInterface $sspInquiryManagementRepository
     * @param \SprykerFeature\Zed\SspAssetManagement\Business\SspAssetManagementFacadeInterface $sspAssetManagementFacade
     */
    public function __construct(
        protected SspInquiryManagementRepositoryInterface $sspInquiryManagementRepository,
        protected SspAssetManagementFacadeInterface $sspAssetManagementFacade
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCollectionTransfer $sspInquiryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function expand(SspInquiryCollectionTransfer $sspInquiryCollectionTransfer): SspInquiryCollectionTransfer
    {
        $sspInquiryIds = array_map(fn ($sspInquiryTransfer) => $sspInquiryTransfer->getIdSspInquiry(), $sspInquiryCollectionTransfer->getSspInquiries()->getArrayCopy());

        $sspAssetSspInquiryCollectionTransfer = $this->sspInquiryManagementRepository->getSspInquirySspAssetCollection(
            (new SspInquiryCriteriaTransfer())->setSspInquiryConditions((new SspInquiryConditionsTransfer())->setSspInquiryIds($sspInquiryIds)),
        );

        $sspAssetCollectionTransfer = $this->sspAssetManagementFacade->getSspAssetCollection((new SspAssetCriteriaTransfer())->setSspAssetConditions(
            (new SspAssetConditionsTransfer())->setSspAssetIds(
                array_map(fn (SspInquiryTransfer $sspAssetSspInquiryTransfer) => $sspAssetSspInquiryTransfer->getSspAssetOrFail()->getIdSspAssetOrFail(), $sspAssetSspInquiryCollectionTransfer->getSspInquiries()->getArrayCopy()),
            ),
        ));

        $sspAssetsGroupedBySspAssetId = [];
        foreach ($sspAssetCollectionTransfer->getSspAssets() as $sspAssetTransfer) {
            $sspAssetsGroupedBySspAssetId[$sspAssetTransfer->getIdSspAssetOrFail()] = $sspAssetTransfer;
        }

        foreach ($sspInquiryCollectionTransfer->getSspInquiries() as $sspInquiryTransfer) {
            if ($sspInquiryTransfer->getSspAsset()) {
                continue;
            }

            foreach ($sspAssetSspInquiryCollectionTransfer->getSspInquiries() as $sspAssetSspInquiryTransfer) {
                if ($sspInquiryTransfer->getIdSspInquiry() !== $sspAssetSspInquiryTransfer->getIdSspInquiry()) {
                    continue;
                }
                $sspInquiryTransfer->setSspAsset($sspAssetsGroupedBySspAssetId[$sspAssetSspInquiryTransfer->getSspAssetOrFail()->getIdSspAssetOrFail()]);
            }
        }

        return $sspInquiryCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return bool
     */
    public function isApplicable(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): bool
    {
        return $sspInquiryCriteriaTransfer->getInclude() && $sspInquiryCriteriaTransfer->getInclude()->getWithSspAsset();
    }
}
