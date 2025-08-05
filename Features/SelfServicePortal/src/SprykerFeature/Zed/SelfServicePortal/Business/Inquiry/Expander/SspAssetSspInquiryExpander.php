<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander;

use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Reader\SspAssetReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class SspAssetSspInquiryExpander implements SspInquiryExpanderInterface
{
    public function __construct(
        protected SelfServicePortalRepositoryInterface $selfServicePortalRepository,
        protected SspAssetReaderInterface $sspAssetReader
    ) {
    }

    public function expand(SspInquiryCollectionTransfer $sspInquiryCollectionTransfer): SspInquiryCollectionTransfer
    {
        $sspInquiryIds = array_map(fn ($sspInquiryTransfer) => $sspInquiryTransfer->getIdSspInquiry(), $sspInquiryCollectionTransfer->getSspInquiries()->getArrayCopy());

        $sspAssetSspInquiryCollectionTransfer = $this->selfServicePortalRepository->getSspInquirySspAssetCollection(
            (new SspInquiryCriteriaTransfer())->setSspInquiryConditions((new SspInquiryConditionsTransfer())->setSspInquiryIds($sspInquiryIds)),
        );

        $sspAssetCollectionTransfer = $this->sspAssetReader->getSspAssetCollection((new SspAssetCriteriaTransfer())->setSspAssetConditions(
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

    public function isApplicable(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): bool
    {
        return $sspInquiryCriteriaTransfer->getInclude() && $sspInquiryCriteriaTransfer->getInclude()->getWithSspAsset();
    }
}
