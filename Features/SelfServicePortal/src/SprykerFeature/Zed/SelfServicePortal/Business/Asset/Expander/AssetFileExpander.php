<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\Expander;

use Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentFileConditionsTransfer;
use Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentFileSearchConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig as SharedSelfServicePortalConfig;
use SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Reader\CompanyFileReaderInterface;

class AssetFileExpander implements AssetFileExpanderInterface
{
    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Reader\CompanyFileReaderInterface $companyFileReader
     */
    public function __construct(protected CompanyFileReaderInterface $companyFileReader)
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

        $fileAttachmentFileCollectionTransfer = $this->companyFileReader->getFileAttachmentFileCollectionAccordingToPermissions(
            (new FileAttachmentFileCriteriaTransfer())
                ->setCompanyUser($sspAssetCriteriaTransfer->getCompanyUserOrFail())
                ->setFileAttachmentFileConditions(
                    (new FileAttachmentFileConditionsTransfer())
                        ->setAssetReferences($sspAssetReferences)
                        ->addEntityType(SharedSelfServicePortalConfig::ENTITY_TYPE_SSP_ASSET),
                )
                ->setFileAttachmentFileSearchConditions((new FileAttachmentFileSearchConditionsTransfer())),
        );

        foreach ($sspAssetCollectionTransfer->getSspAssets() as $sspAssetTransfer) {
            $sspAssetTransfer->setFileAttachmentFileCollection(new FileAttachmentFileCollectionTransfer());
            foreach ($fileAttachmentFileCollectionTransfer->getFileAttachments() as $fileAttachmentTransfer) {
                if ($fileAttachmentTransfer->getEntityNameOrFail() !== SharedSelfServicePortalConfig::ENTITY_TYPE_SSP_ASSET) {
                    continue;
                }
                if ($fileAttachmentTransfer->getEntityId() === $sspAssetTransfer->getIdSspAssetOrFail()) {
                    $sspAssetTransfer->getFileAttachmentFileCollectionOrFail()->addFileAttachment($fileAttachmentTransfer);
                }
            }
        }

        return $sspAssetCollectionTransfer;
    }
}
