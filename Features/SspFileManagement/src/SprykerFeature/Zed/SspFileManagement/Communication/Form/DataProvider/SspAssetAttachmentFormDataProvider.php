<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Communication\Form\DataProvider;

use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetIncludeTransfer;
use SprykerFeature\Zed\SspAssetManagement\Business\SspAssetManagementFacadeInterface;
use SprykerFeature\Zed\SspAssetManagement\Communication\Form\AssetAttachmentForm;
use SprykerFeature\Zed\SspFileManagement\Communication\Form\SspAssetAttachmentForm;

class SspAssetAttachmentFormDataProvider
{
    /**
     * @param \SprykerFeature\Zed\SspAssetManagement\Business\SspAssetManagementFacadeInterface $sspAssetManagementFacade
     */
    public function __construct(
        protected SspAssetManagementFacadeInterface $sspAssetManagementFacade
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
     *
     * @return array<string, mixed>
     */
    public function getOptions(int $idFile): array
    {
        return [
            SspAssetAttachmentForm::OPTION_SSP_ASSET_CHOICES => $this->getSspAssetChoices(),
            AssetAttachmentForm::OPTION_ID_FILE => $idFile,
        ];
    }

    /**
     * @return array<string, int>
     */
    protected function getSspAssetChoices(): array
    {
        $sspAssetIds = [];

        $sspAssetConditionsTransfer = new SspAssetConditionsTransfer();
        $sspAssetConditionsTransfer->setSspAssetIds($sspAssetIds);

        $sspAssetCriteriaTransfer = new SspAssetCriteriaTransfer();
        $sspAssetCriteriaTransfer->setSspAssetConditions($sspAssetConditionsTransfer)
            ->setInclude((new SspAssetIncludeTransfer())->setWithCompanyBusinessUnit(true));

        $sspAssetCollectionTransfer = $this->sspAssetManagementFacade->getSspAssetCollection($sspAssetCriteriaTransfer);
        $assetChoices = [];

        foreach ($sspAssetCollectionTransfer->getSspAssets() as $sspAssetTransfer) {
            $assetName = $sspAssetTransfer->getName() . ': ' . $sspAssetTransfer->getReference();

            $assetChoices[$assetName] = $sspAssetTransfer->getIdSspAssetOrFail();
        }

        return $assetChoices;
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetCollectionTransfer $sspAssetCollectionTransfer
     *
     * @return array<string, mixed>
     */
    protected function formatAssetsForAutocomplete(SspAssetCollectionTransfer $sspAssetCollectionTransfer): array
    {
        $autocompleteData = ['results' => []];

        foreach ($sspAssetCollectionTransfer->getSspAssets() as $sspAssetTransfer) {
            $text = sprintf(
                '%s, %s (%s, %s)',
                $sspAssetTransfer->getName(),
                $sspAssetTransfer->getReference(),
                $sspAssetTransfer->getCompanyBusinessUnit()?->getName(),
                $sspAssetTransfer->getCompanyBusinessUnit()?->getCompany()?->getName(),
            );

            $autocompleteData['results'][] = [
                'id' => $sspAssetTransfer->getIdSspAsset(),
                'text' => $text,
            ];
        }

        return $autocompleteData;
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
        $sspAssetIncludeTransfer->setWithCompanyBusinessUnit(true);

        $sspAssetCriteriaTransfer->setSspAssetConditions($sspAssetConditionsTransfer)
            ->setInclude($sspAssetIncludeTransfer);

        $sspAssetCollectionTransfer = $this->sspAssetManagementFacade->getSspAssetCollection($sspAssetCriteriaTransfer);

        return $sspAssetCollectionTransfer->getSspAssets()->getArrayCopy();
    }
}
