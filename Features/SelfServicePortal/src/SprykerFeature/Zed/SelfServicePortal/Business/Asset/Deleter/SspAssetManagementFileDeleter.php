<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\Deleter;

use Generated\Shared\Transfer\FileCollectionTransfer;
use Generated\Shared\Transfer\SspAssetCollectionRequestTransfer;
use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Reader\SspAssetReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Writer\SspAssetWriterInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface;

class SspAssetManagementFileDeleter implements SspAssetManagementFileDeleterInterface
{
    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Business\Asset\Reader\SspAssetReaderInterface $sspAssetReader
     * @param \SprykerFeature\Zed\SelfServicePortal\Business\Asset\Writer\SspAssetWriterInterface $sspAssetWriter
     * @param \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface $entityManager
     */
    public function __construct(
        protected SspAssetReaderInterface $sspAssetReader,
        protected SspAssetWriterInterface $sspAssetWriter,
        protected SelfServicePortalEntityManagerInterface $entityManager
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\FileCollectionTransfer $fileCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\FileCollectionTransfer
     */
    public function deleteSspAssetRelationsByFileCollection(FileCollectionTransfer $fileCollectionTransfer): FileCollectionTransfer
    {
        $fileIds = [];
        foreach ($fileCollectionTransfer->getFiles() as $fileTransfer) {
            $fileIds[] = $fileTransfer->getIdFileOrFail();
        }

        $sspAssetCollection = $this->sspAssetReader->getSspAssetCollection(
            (new SspAssetCriteriaTransfer())->setSspAssetConditions(
                (new SspAssetConditionsTransfer())->setImageFileIds($fileIds),
            ),
        );

        foreach ($sspAssetCollection->getSspAssets() as $sspAssetTransfer) {
            $sspAssetTransfer->setImage(null);
        }

        $this->sspAssetWriter->updateSspAssetCollection(
            (new SspAssetCollectionRequestTransfer())->setSspAssets($sspAssetCollection->getSspAssets()),
        );

        return $fileCollectionTransfer;
    }
}
