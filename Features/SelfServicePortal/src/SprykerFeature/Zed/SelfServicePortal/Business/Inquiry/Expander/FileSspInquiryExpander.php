<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander;

use ArrayObject;
use Generated\Shared\Transfer\FileConditionsTransfer;
use Generated\Shared\Transfer\FileCriteriaTransfer;
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
        $sspInquiryIds = $this->extractSspInquiryIds($sspInquiryCollectionTransfer);
        $fileIdToInquiryIdMapping = $this->buildFileIdToInquiryIdMapping($sspInquiryIds);

        if ($fileIdToInquiryIdMapping === []) {
            return $sspInquiryCollectionTransfer;
        }

        $fileTransfers = $this->getFileTransfersByFileIds(array_keys($fileIdToInquiryIdMapping));
        $fileTransfersGroupedByInquiryId = $this->groupFileTransfersByInquiryId($fileTransfers, $fileIdToInquiryIdMapping);

        return $this->expandInquiriesWithFiles($sspInquiryCollectionTransfer, $fileTransfersGroupedByInquiryId);
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCollectionTransfer $sspInquiryCollectionTransfer
     *
     * @return list<int>
     */
    protected function extractSspInquiryIds(SspInquiryCollectionTransfer $sspInquiryCollectionTransfer): array
    {
        return array_map(
            static fn ($sspInquiryTransfer) => $sspInquiryTransfer->getIdSspInquiry(),
            $sspInquiryCollectionTransfer->getSspInquiries()->getArrayCopy(),
        );
    }

    /**
     * @param list<int> $sspInquiryIds
     *
     * @return array<int, int>
     */
    protected function buildFileIdToInquiryIdMapping(array $sspInquiryIds): array
    {
        $sspInquiryFileCollectionTransfer = $this->selfServicePortalRepository->getSspInquiryFileCollection(
            (new SspInquiryCriteriaTransfer())->setSspInquiryConditions(
                (new SspInquiryConditionsTransfer())->setSspInquiryIds($sspInquiryIds),
            ),
        );

        $fileIdToInquiryIdMapping = [];

        foreach ($sspInquiryFileCollectionTransfer->getSspInquiries() as $sspInquiryTransfer) {
            foreach ($sspInquiryTransfer->getFiles() as $fileTransfer) {
                $fileIdToInquiryIdMapping[$fileTransfer->getIdFileOrFail()] = $sspInquiryTransfer->getIdSspInquiryOrFail();
            }
        }

        return $fileIdToInquiryIdMapping;
    }

    /**
     * @param list<int> $fileIds
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\FileTransfer>
     */
    protected function getFileTransfersByFileIds(array $fileIds): ArrayObject
    {
        $fileCriteriaTransfer = (new FileCriteriaTransfer())
            ->setFileConditions(
                (new FileConditionsTransfer())->setFileIds($fileIds),
            );

        $fileCollectionTransfer = $this->fileManagerFacade->getFileCollection($fileCriteriaTransfer);

        return $fileCollectionTransfer->getFiles();
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\FileTransfer> $fileTransfers
     * @param array<int, int> $fileIdToInquiryIdMapping
     *
     * @return array<int, list<\Generated\Shared\Transfer\FileTransfer>>
     */
    protected function groupFileTransfersByInquiryId(ArrayObject $fileTransfers, array $fileIdToInquiryIdMapping): array
    {
        $fileTransfersGroupedByInquiryId = [];

        foreach ($fileTransfers as $fileTransfer) {
            $inquiryId = $fileIdToInquiryIdMapping[$fileTransfer->getIdFileOrFail()];
            $fileTransfersGroupedByInquiryId[$inquiryId][] = $fileTransfer;
        }

        return $fileTransfersGroupedByInquiryId;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCollectionTransfer $sspInquiryCollectionTransfer
     * @param array<int, list<\Generated\Shared\Transfer\FileTransfer>> $fileTransfersGroupedByInquiryId
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    protected function expandInquiriesWithFiles(
        SspInquiryCollectionTransfer $sspInquiryCollectionTransfer,
        array $fileTransfersGroupedByInquiryId
    ): SspInquiryCollectionTransfer {
        foreach ($sspInquiryCollectionTransfer->getSspInquiries() as $sspInquiryTransfer) {
            $fileTransfers = $fileTransfersGroupedByInquiryId[$sspInquiryTransfer->getIdSspInquiryOrFail()] ?? [];

            foreach ($fileTransfers as $fileTransfer) {
                $sspInquiryTransfer->addFile($fileTransfer);
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
