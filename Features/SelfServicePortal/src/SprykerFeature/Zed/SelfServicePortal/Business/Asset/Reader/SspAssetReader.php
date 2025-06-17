<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\Reader;

use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemReadException;
use Spryker\Zed\FileManager\Business\FileManagerFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Permission\SspAssetCustomerPermissionExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class SspAssetReader implements SspAssetReaderInterface
{
    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface $selfServicePortalRepository
     * @param \Spryker\Zed\FileManager\Business\FileManagerFacadeInterface $fileManagerFacade
     * @param array<\SprykerFeature\Zed\SelfServicePortal\Dependency\Plugin\SspAssetManagementExpanderPluginInterface> $sspAssetExpanderPlugins
     * @param \SprykerFeature\Zed\SelfServicePortal\Business\Asset\Permission\SspAssetCustomerPermissionExpanderInterface $sspAssetCustomerPermissionExpander
     */
    public function __construct(
        protected SelfServicePortalRepositoryInterface $selfServicePortalRepository,
        protected FileManagerFacadeInterface $fileManagerFacade,
        protected array $sspAssetExpanderPlugins,
        protected SspAssetCustomerPermissionExpanderInterface $sspAssetCustomerPermissionExpander
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionTransfer
     */
    public function getSspAssetCollection(SspAssetCriteriaTransfer $sspAssetCriteriaTransfer): SspAssetCollectionTransfer
    {
        $sspAssetCollectionTransfer = $this->selfServicePortalRepository->getSspAssetCollection(
            $this->sspAssetCustomerPermissionExpander->expand($sspAssetCriteriaTransfer),
        );

        foreach ($this->sspAssetExpanderPlugins as $sspAssetExpanderPlugin) {
            $sspAssetCollectionTransfer = $sspAssetExpanderPlugin->expand($sspAssetCollectionTransfer, $sspAssetCriteriaTransfer);
        }

        if ($sspAssetCriteriaTransfer->getInclude() !== null && $sspAssetCriteriaTransfer->getIncludeOrFail()->getWithImageFile()) {
            $sspAssetCollectionTransfer = $this->expandWithFile($sspAssetCollectionTransfer);
        }

        return $sspAssetCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetCollectionTransfer $sspAssetCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionTransfer
     */
    public function expandWithFile(SspAssetCollectionTransfer $sspAssetCollectionTransfer): SspAssetCollectionTransfer
    {
        $fileIds = [];

        foreach ($sspAssetCollectionTransfer->getSspAssets() as $sspAssetTransfer) {
            if ($sspAssetTransfer->getImage()) {
                $fileIds[] = $sspAssetTransfer->getImage()->getIdFileOrFail();
            }
        }

        try {
            $fileManagerDataTransfers = $this->fileManagerFacade->getFilesByIds($fileIds);
        } catch (FileSystemReadException $fileSystemReadException) {
            return $sspAssetCollectionTransfer;
        }

        foreach ($sspAssetCollectionTransfer->getSspAssets() as $sspAssetTransfer) {
            if (!$sspAssetTransfer->getImage()) {
                continue;
            }
            foreach ($fileManagerDataTransfers as $fileManagerDataTransfer) {
                if ($sspAssetTransfer->getImageOrFail()->getIdFile() === $fileManagerDataTransfer->getFileOrFail()->getIdFile()) {
                    $sspAssetTransfer->setImage($fileManagerDataTransfer->getFile());
                }
            }
        }

        return $sspAssetCollectionTransfer;
    }
}
