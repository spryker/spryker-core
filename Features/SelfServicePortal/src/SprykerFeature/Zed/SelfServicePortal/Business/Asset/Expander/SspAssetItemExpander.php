<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\Expander;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class SspAssetItemExpander implements SspAssetItemExpanderInterface
{
    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface $sspAssetManagementRepository
     */
    public function __construct(
        protected SelfServicePortalRepositoryInterface $sspAssetManagementRepository
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartItemsWithSspAssets(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        $assetReferences = $this->extractAssetReferencesFromItems($cartChangeTransfer);

        if (!$assetReferences) {
            return $cartChangeTransfer;
        }

        $assetCollectionTransfer = $this->getAssetsByReferences($assetReferences);
        $assetTransfersIndexedByReference = $this->indexAssetsByReference($assetCollectionTransfer);

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $sspAssetTransfer = $itemTransfer->getSspAsset();

            if (!$sspAssetTransfer) {
                continue;
            }

            $assetReference = $sspAssetTransfer->getReference();

            if (isset($assetTransfersIndexedByReference[$assetReference])) {
                $itemTransfer->setSspAsset($assetTransfersIndexedByReference[$assetReference]);
            }
        }

        return $cartChangeTransfer;
    }

    /**
     * @param array<string> $assetReferences
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionTransfer
     */
    protected function getAssetsByReferences(array $assetReferences): object
    {
        $sspAssetConditionsTransfer = new SspAssetConditionsTransfer();
        $sspAssetConditionsTransfer->setReferences($assetReferences);

        $sspAssetCriteriaTransfer = new SspAssetCriteriaTransfer();
        $sspAssetCriteriaTransfer->setSspAssetConditions($sspAssetConditionsTransfer);

        return $this->sspAssetManagementRepository->getSspAssetCollection($sspAssetCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetCollectionTransfer $assetCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\SspAssetTransfer>
     */
    protected function indexAssetsByReference(object $assetCollectionTransfer): array
    {
        $assetTransfersIndexedByReference = [];

        foreach ($assetCollectionTransfer->getSspAssets() as $sspAssetTransfer) {
            $assetTransfersIndexedByReference[$sspAssetTransfer->getReference()] = $sspAssetTransfer;
        }

        return $assetTransfersIndexedByReference;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array<string>
     */
    protected function extractAssetReferencesFromItems(CartChangeTransfer $cartChangeTransfer): array
    {
        $assetReferences = [];

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $sspAssetTransfer = $itemTransfer->getSspAsset();

            if ($sspAssetTransfer && $sspAssetTransfer->getReference()) {
                $assetReferences[] = $sspAssetTransfer->getReference();
            }
        }

        return array_unique($assetReferences);
    }
}
