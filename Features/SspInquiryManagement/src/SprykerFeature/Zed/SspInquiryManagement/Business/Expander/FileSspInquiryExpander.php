<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business\Expander;

use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Spryker\Zed\FileManager\Business\FileManagerFacadeInterface;
use SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementRepositoryInterface;

class FileSspInquiryExpander implements SspInquiryExpanderInterface
{
    /**
     * @param \Spryker\Zed\FileManager\Business\FileManagerFacadeInterface $fileManagerFacade
     * @param \SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementRepositoryInterface $sspInquiryManagementRepository
     */
    public function __construct(
        protected FileManagerFacadeInterface $fileManagerFacade,
        protected SspInquiryManagementRepositoryInterface $sspInquiryManagementRepository
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCollectionTransfer $sspInquiryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function expand(SspInquiryCollectionTransfer $sspInquiryCollectionTransfer): SspInquiryCollectionTransfer
    {
        $sspInquiryIds = array_map(fn ($sspInquiryTransfer) => $sspInquiryTransfer->getIdSspInquiry(), $sspInquiryCollectionTransfer->getSspInquiries()->getArrayCopy());

        $fileCollectionTransfer = $this->sspInquiryManagementRepository->getSspInquiryFileCollection(
            (new SspInquiryCriteriaTransfer())->setSspInquiryConditions((new SspInquiryConditionsTransfer())->setSspInquiryIds($sspInquiryIds)),
        );

        $allFileIds = [];
        foreach ($fileCollectionTransfer->getSspInquiries() as $sspInquiryFileTransfer) {
            foreach ($sspInquiryFileTransfer->getFiles() as $fileTransfer) {
                $allFileIds[] = (int)$fileTransfer->getIdFile();
            }

            foreach ($sspInquiryCollectionTransfer->getSspInquiries() as $sspInquiryTransfer) {
                if ($sspInquiryFileTransfer->getIdSspInquiry() !== $sspInquiryTransfer->getIdSspInquiry()) {
                    continue;
                }
                 $sspInquiryTransfer->setFiles($sspInquiryFileTransfer->getFiles());
            }
        }

        $fileManagerDataTransfers = $this->fileManagerFacade->getFilesByIds($allFileIds);
        $fileManagerDataTransfersMappedById = [];
        foreach ($fileManagerDataTransfers as $fileManagerDataTransfer) {
            $fileManagerDataTransfersMappedById[$fileManagerDataTransfer->getFileOrFail()->getIdFile()] = $fileManagerDataTransfer;
        }

        foreach ($fileCollectionTransfer->getSspInquiries() as $sspInquiryTransfer) {
            foreach ($sspInquiryTransfer->getFiles() as $fileTransfer) {
                $fileManagerDataTransfer = $fileManagerDataTransfersMappedById[$fileTransfer->getIdFile()] ?? null;
                if (!$fileManagerDataTransfer) {
                    continue;
                }
                $fileTransfer->addFileInfo($fileManagerDataTransfer->getFileInfoOrFail());
                $fileTransfer->setFileName($fileManagerDataTransfer->getFileOrFail()->getFileName());
            }
        }

        return $sspInquiryCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return bool
     */
    public function isApplicable(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): bool
    {
        return $sspInquiryCriteriaTransfer->getInclude() && $sspInquiryCriteriaTransfer->getInclude()->getWithFiles();
    }
}
