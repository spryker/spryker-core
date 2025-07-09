<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\Expander;

use Generated\Shared\Transfer\FileAttachmentCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentConditionsTransfer;
use Generated\Shared\Transfer\FileAttachmentCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentSearchConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class AssetFileExpander implements AssetFileExpanderInterface
{
    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface $selfServicePortalRepository
     */
    public function __construct(protected SelfServicePortalRepositoryInterface $selfServicePortalRepository)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetCollectionTransfer $sspAssetCollectionTransfer
     * @param \Generated\Shared\Transfer\SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionTransfer
     */
    public function expandAssetCollectionWithFiles(
        SspAssetCollectionTransfer $sspAssetCollectionTransfer,
        SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
    ): SspAssetCollectionTransfer {
        if (!$sspAssetCriteriaTransfer->getInclude() || !$sspAssetCriteriaTransfer->getInclude()->getWithFiles()) {
            return $sspAssetCollectionTransfer;
        }

        $sspAssetReferences = [];
        foreach ($sspAssetCollectionTransfer->getSspAssets() as $sspAssetTransfer) {
            $sspAssetReferences[] = $sspAssetTransfer->getReferenceOrFail();
        }

        $fileAttachmentCollectionTransfer = $this->selfServicePortalRepository->getFileAttachmentCollection(
            (new FileAttachmentCriteriaTransfer())
                ->setCompanyUser($sspAssetCriteriaTransfer->getCompanyUserOrFail())
                ->setFileAttachmentConditions(
                    (new FileAttachmentConditionsTransfer())
                        ->setAssetReferences($sspAssetReferences),
                )
                ->setWithSspAssetRelation(true)
                ->setFileAttachmentSearchConditions((new FileAttachmentSearchConditionsTransfer())),
        );

        foreach ($sspAssetCollectionTransfer->getSspAssets() as $sspAssetTransfer) {
            $sspAssetTransfer->setFileAttachmentCollection(new FileAttachmentCollectionTransfer());
            foreach ($fileAttachmentCollectionTransfer->getFileAttachments() as $fileAttachmentTransfer) {
                foreach ($fileAttachmentTransfer->getSspAssetCollectionOrFail()->getSspAssets() as $fileSspAssetTransfer) {
                    if ($fileSspAssetTransfer->getIdSspAssetOrFail() === $sspAssetTransfer->getIdSspAssetOrFail()) {
                        $sspAssetTransfer->getFileAttachmentCollectionOrFail()->addFileAttachment($fileAttachmentTransfer);
                    }
                }
            }
        }

        return $sspAssetCollectionTransfer;
    }
}
