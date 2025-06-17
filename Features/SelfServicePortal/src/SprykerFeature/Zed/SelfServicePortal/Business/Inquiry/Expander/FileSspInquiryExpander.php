<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander;

use ArrayObject;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Spryker\Zed\FileManager\Business\FileManagerFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class FileSspInquiryExpander implements SspInquiryExpanderInterface
{
    /**
     * @param \Spryker\Zed\FileManager\Business\FileManagerFacadeInterface $fileManagerFacade
     * @param \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface $selfServicePortalRepository
     */
    public function __construct(
        protected FileManagerFacadeInterface $fileManagerFacade,
        protected SelfServicePortalRepositoryInterface $selfServicePortalRepository
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

        $sspInquiryFileCollectionTransfer = $this->selfServicePortalRepository->getSspInquiryFileCollection(
            (new SspInquiryCriteriaTransfer())->setSspInquiryConditions((new SspInquiryConditionsTransfer())->setSspInquiryIds($sspInquiryIds)),
        );

        $sspInquiryIdMappedByFileId = [];

        foreach ($sspInquiryFileCollectionTransfer->getSspInquiries() as $sspInquiryTransfer) {
            foreach ($sspInquiryTransfer->getFiles() as $fileTransfer) {
                $sspInquiryIdMappedByFileId[$fileTransfer->getIdFileOrFail()] = $sspInquiryTransfer->getIdSspInquiryOrFail();
            }
        }

        $fileManagerDataTransfers = $this->fileManagerFacade->getFilesByIds(array_keys($sspInquiryIdMappedByFileId));
        $fileManagerDataTransfersGroupedBySspInquiryId = [];
        foreach ($fileManagerDataTransfers as $fileManagerDataTransfer) {
            $fileManagerDataTransfersGroupedBySspInquiryId[$sspInquiryIdMappedByFileId[$fileManagerDataTransfer->getFileOrFail()->getIdFileOrFail()]][] = $fileManagerDataTransfer;
        }

        foreach ($sspInquiryCollectionTransfer->getSspInquiries() as $sspInquiryTransfer) {
            $sspInquiryTransfer->setFiles(new ArrayObject());
            $fileManagerDataTransfers = $fileManagerDataTransfersGroupedBySspInquiryId[$sspInquiryTransfer->getIdSspInquiryOrFail()] ?? [];
            foreach ($fileManagerDataTransfers as $fileManagerDataTransfer) {
                $sspInquiryTransfer->getFiles()->append((new FileTransfer())
                    ->addFileInfo($fileManagerDataTransfer->getFileInfoOrFail())
                    ->setFileName($fileManagerDataTransfer->getFileOrFail()->getFileName()));
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
