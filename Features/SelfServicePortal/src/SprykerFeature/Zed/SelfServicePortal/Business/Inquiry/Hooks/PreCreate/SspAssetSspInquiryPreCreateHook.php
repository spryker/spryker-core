<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Hooks\PreCreate;

use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Reader\SspAssetReaderInterface;

class SspAssetSspInquiryPreCreateHook implements SspInquiryPreCreateHookInterface
{
    public function __construct(protected SspAssetReaderInterface $sspAssetReader)
    {
    }

    public function execute(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer
    {
        $sspInquiryTransfer->requireSspAsset();

        $sspAssetCollectionTransfer = $this->sspAssetReader->getSspAssetCollection(
            (new SspAssetCriteriaTransfer())
                ->setSspAssetConditions(
                    (new SspAssetConditionsTransfer())->addReference($sspInquiryTransfer->getSspAssetOrFail()->getReferenceOrFail()),
                ),
        );

        if ($sspAssetCollectionTransfer->getSspAssets()->count() === 0) {
            return $sspInquiryTransfer;
        }

        $sspInquiryTransfer->setSspAsset($sspAssetCollectionTransfer->getSspAssets()->getIterator()->current());

        return $sspInquiryTransfer;
    }

    public function isApplicable(SspInquiryTransfer $sspInquiryTransfer): bool
    {
        return $sspInquiryTransfer->getType() === SelfServicePortalConfig::SSP_ASSET_SSP_INQUIRY_SOURCE;
    }
}
