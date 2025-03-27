<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PreCreate;

use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use SprykerFeature\Shared\SspInquiryManagement\SspInquiryManagementConfig;
use SprykerFeature\Zed\SspAssetManagement\Business\SspAssetManagementFacadeInterface;

class SspAssetSspInquiryPreCreateHook implements SspInquiryPreCreateHookInterface
{
    /**
     * @param \SprykerFeature\Zed\SspAssetManagement\Business\SspAssetManagementFacadeInterface $sspAssetManagementFacade
     */
    public function __construct(protected SspAssetManagementFacadeInterface $sspAssetManagementFacade)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryTransfer
     */
    public function execute(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer
    {
        $sspInquiryTransfer->requireSspAsset();

        $sspAssetCollectionTransfer = $this->sspAssetManagementFacade->getSspAssetCollection(
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

    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @return bool
     */
    public function isApplicable(SspInquiryTransfer $sspInquiryTransfer): bool
    {
        return $sspInquiryTransfer->getType() === SspInquiryManagementConfig::SSP_ASSET_SSP_INQUIRY_SOURCE;
    }
}
