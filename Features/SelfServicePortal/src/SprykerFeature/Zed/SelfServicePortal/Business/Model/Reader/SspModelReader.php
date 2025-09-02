<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Model\Reader;

use Generated\Shared\Transfer\SspModelCollectionTransfer;
use Generated\Shared\Transfer\SspModelCriteriaTransfer;
use Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemReadException;
use Spryker\Zed\FileManager\Business\FileManagerFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class SspModelReader implements SspModelReaderInterface
{
    public function __construct(
        protected SelfServicePortalRepositoryInterface $selfServicePortalRepository,
        protected FileManagerFacadeInterface $fileManagerFacade
    ) {
    }

    public function getSspModelCollection(SspModelCriteriaTransfer $sspModelCriteriaTransfer): SspModelCollectionTransfer
    {
        $sspModelCollectionTransfer = $this->selfServicePortalRepository->getSspModelCollection($sspModelCriteriaTransfer);

        if ($sspModelCriteriaTransfer->getInclude() !== null && $sspModelCriteriaTransfer->getIncludeOrFail()->getWithImageFile()) {
            $sspModelCollectionTransfer = $this->expandWithFile($sspModelCollectionTransfer);
        }

        return $sspModelCollectionTransfer;
    }

    public function expandWithFile(SspModelCollectionTransfer $sspModelCollectionTransfer): SspModelCollectionTransfer
    {
        $fileIds = [];

        foreach ($sspModelCollectionTransfer->getSspModels() as $sspModelTransfer) {
            if ($sspModelTransfer->getImage()) {
                $fileIds[] = $sspModelTransfer->getImage()->getIdFileOrFail();
            }
        }

        try {
            $fileManagerDataTransfers = $this->fileManagerFacade->getFilesByIds($fileIds);
        } catch (FileSystemReadException $fileSystemReadException) {
            return $sspModelCollectionTransfer;
        }

        $sspModelCollectionTransfer = $this->expandWithImage($sspModelCollectionTransfer, $fileManagerDataTransfers);

        return $sspModelCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspModelCollectionTransfer $sspModelCollectionTransfer
     * @param array<\Generated\Shared\Transfer\FileManagerDataTransfer> $fileManagerDataTransfers
     *
     * @return \Generated\Shared\Transfer\SspModelCollectionTransfer
     */
    protected function expandWithImage(SspModelCollectionTransfer $sspModelCollectionTransfer, array $fileManagerDataTransfers): SspModelCollectionTransfer
    {
        foreach ($sspModelCollectionTransfer->getSspModels() as $sspModelTransfer) {
            if (!$sspModelTransfer->getImage()) {
                continue;
            }
            foreach ($fileManagerDataTransfers as $fileManagerDataTransfer) {
                if ($sspModelTransfer->getImageOrFail()->getIdFile() === $fileManagerDataTransfer->getFileOrFail()->getIdFile()) {
                    $sspModelTransfer->setImage($fileManagerDataTransfer->getFile());
                }
            }
        }

        return $sspModelCollectionTransfer;
    }
}
