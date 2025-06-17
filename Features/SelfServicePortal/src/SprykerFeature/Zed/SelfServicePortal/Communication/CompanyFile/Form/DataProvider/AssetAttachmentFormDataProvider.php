<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\DataProvider;

use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetIncludeTransfer;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Form\AssetAttachmentForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\SspAssetAttachmentForm;

class AssetAttachmentFormDataProvider
{
 /**
  * @param \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface $selfServicePortalFacade
  */
    public function __construct(
        protected SelfServicePortalFacadeInterface $selfServicePortalFacade
    ) {
    }

    /**
     * @param array<string, mixed>|null $formData
     *
     * @return array<string, mixed>
     */
    public function getData(?array $formData = null): array
    {
        if (!$formData) {
            return [];
        }

        return $formData;
    }

    /**
     * @param int $idFile
     * @param array<string> $sspAssetIds
     *
     * @return array<string, mixed>
     */
    public function getOptions(int $idFile, array $sspAssetIds): array
    {
        return [
            SspAssetAttachmentForm::OPTION_SSP_ASSET_CHOICES => $this->getSspAssetChoices($sspAssetIds),
            AssetAttachmentForm::OPTION_ID_FILE => $idFile,
        ];
    }

    /**
     * @param array<string> $sspAssetIds
     *
     * @return array<string, int>
     */
    protected function getSspAssetChoices(array $sspAssetIds): array
    {
        if (!$sspAssetIds) {
            return [];
        }

        $sspAssetConditionsTransfer = new SspAssetConditionsTransfer();

        $sspAssetConditionsTransfer->setSspAssetIds($sspAssetIds); // @phpstan-ignore-line

        $sspAssetCriteriaTransfer = new SspAssetCriteriaTransfer();
        $sspAssetCriteriaTransfer->setSspAssetConditions($sspAssetConditionsTransfer)
            ->setInclude((new SspAssetIncludeTransfer())->setWithOwnerCompanyBusinessUnit(true));

        $sspAssetCollectionTransfer = $this->selfServicePortalFacade->getSspAssetCollection($sspAssetCriteriaTransfer);
        $assetChoices = [];

        foreach ($sspAssetCollectionTransfer->getSspAssets() as $sspAssetTransfer) {
            $assetName = $sspAssetTransfer->getName() . ': ' . $sspAssetTransfer->getReference();

            $assetChoices[$assetName] = $sspAssetTransfer->getIdSspAssetOrFail();
        }

        return $assetChoices;
    }

    /**
     * @param array<int> $assetIds
     *
     * @return array<\Generated\Shared\Transfer\SspAssetTransfer>
     */
    public function getSspAssetCollectionByIds(array $assetIds): array
    {
        $sspAssetConditionsTransfer = new SspAssetConditionsTransfer();
        $sspAssetConditionsTransfer->setSspAssetIds($assetIds);

        $sspAssetCriteriaTransfer = new SspAssetCriteriaTransfer();
        $sspAssetIncludeTransfer = new SspAssetIncludeTransfer();
        $sspAssetIncludeTransfer->setWithOwnerCompanyBusinessUnit(true);

        $sspAssetCriteriaTransfer->setSspAssetConditions($sspAssetConditionsTransfer)
            ->setInclude($sspAssetIncludeTransfer);

        $sspAssetCollectionTransfer = $this->selfServicePortalFacade->getSspAssetCollection($sspAssetCriteriaTransfer);

        return $sspAssetCollectionTransfer->getSspAssets()->getArrayCopy();
    }
}
