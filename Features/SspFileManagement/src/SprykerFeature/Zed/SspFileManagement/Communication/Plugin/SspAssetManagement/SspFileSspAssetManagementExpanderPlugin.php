<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Communication\Plugin\SspAssetManagement;

use Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentFileConditionsTransfer;
use Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentFileSearchConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use SprykerFeature\Shared\SspFileManagement\SspFileManagementConfig;
use SprykerFeature\Zed\SspAssetManagement\Dependency\Plugin\SspAssetManagementExpanderPluginInterface;

/**
 * @method \SprykerFeature\Zed\SspFileManagement\Business\SspFileManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspFileManagement\Communication\SspFileManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspFileManagement\SspFileManagementConfig getConfig()
 */
class SspFileSspAssetManagementExpanderPlugin extends AbstractPlugin implements SspAssetManagementExpanderPluginInterface
{
    use PermissionAwareTrait;

    /**
     * {@inheritDoc}
     * - Expands the SspAssetCollectionTransfer with files data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspAssetCollectionTransfer $sspAssetCollectionTransfer
     * @param \Generated\Shared\Transfer\SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionTransfer
     */
    public function expand(
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

        $fileAttachmentFileCollectionTransfer = $this->getFacade()->getFileAttachmentFileCollectionAccordingToPermissions(
            (new FileAttachmentFileCriteriaTransfer())
                ->setCompanyUser($sspAssetCriteriaTransfer->getCompanyUserOrFail())
                ->setFileAttachmentFileConditions(
                    (new FileAttachmentFileConditionsTransfer())
                        ->setAssetReferences($sspAssetReferences)
                        ->addEntityType(SspFileManagementConfig::ENTITY_TYPE_SSP_ASSET),
                )
                ->setFileAttachmentFileSearchConditions((new FileAttachmentFileSearchConditionsTransfer())),
        );

        foreach ($sspAssetCollectionTransfer->getSspAssets() as $sspAssetTransfer) {
            $sspAssetTransfer->setFileAttachmentFileCollection(new FileAttachmentFileCollectionTransfer());
            foreach ($fileAttachmentFileCollectionTransfer->getFileAttachments() as $fileAttachmentTransfer) {
                if ($fileAttachmentTransfer->getEntityNameOrFail() !== SspFileManagementConfig::ENTITY_TYPE_SSP_ASSET) {
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
