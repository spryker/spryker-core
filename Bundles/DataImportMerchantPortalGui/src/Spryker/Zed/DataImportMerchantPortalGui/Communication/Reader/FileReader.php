<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchantPortalGui\Communication\Reader;

use Generated\Shared\Transfer\DataImportMerchantFileTransfer;
use Generated\Shared\Transfer\FileSystemStreamTransfer;
use Spryker\Zed\DataImportMerchantPortalGui\Dependency\Service\DataImportMerchantPortalGuiToFileSystemServiceInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;

class FileReader implements FileReaderInterface
{
    /**
     * @param \Spryker\Zed\DataImportMerchantPortalGui\Dependency\Service\DataImportMerchantPortalGuiToFileSystemServiceInterface $fileSystemService
     */
    public function __construct(
        protected DataImportMerchantPortalGuiToFileSystemServiceInterface $fileSystemService
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     *
     * @return mixed|resource
     */
    public function read(DataImportMerchantFileTransfer $dataImportMerchantFileTransfer)
    {
        $fileSystemStreamTransfer = (new FileSystemStreamTransfer())
            ->setFileSystemName($dataImportMerchantFileTransfer->getFileInfoOrFail()->getFileSystemNameOrFail())
            ->setPath($dataImportMerchantFileTransfer->getFileInfoOrFail()->getUploadedUrlOrFail());

        return $this->fileSystemService->readStream($fileSystemStreamTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     *
     * @return array<string, string>
     */
    public function getSourceFileResponseHeaders(DataImportMerchantFileTransfer $dataImportMerchantFileTransfer): array
    {
        return [
            'Content-Disposition' => HeaderUtils::makeDisposition(
                HeaderUtils::DISPOSITION_ATTACHMENT,
                $dataImportMerchantFileTransfer->getFileInfoOrFail()->getOriginalFileNameOrFail(),
            ),
            'Content-Type' => $dataImportMerchantFileTransfer->getFileInfoOrFail()->getContentTypeOrFail(),
            'Expires' => '0',
            'Cache-Control' => 'must-revalidate',
            'Pragma' => 'public',
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     *
     * @return array<string, string>
     */
    public function getErrorsFileResponseHeaders(DataImportMerchantFileTransfer $dataImportMerchantFileTransfer): array
    {
        $fileName = $this->generateErrorsFileName($dataImportMerchantFileTransfer);

        return [
            'Content-Disposition' => HeaderUtils::makeDisposition(HeaderUtils::DISPOSITION_ATTACHMENT, $fileName),
            'Content-Type' => 'application/csv',
            'Expires' => '0',
            'Cache-Control' => 'must-revalidate',
            'Pragma' => 'public',
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     *
     * @return string
     */
    protected function generateErrorsFileName(DataImportMerchantFileTransfer $dataImportMerchantFileTransfer): string
    {
        return sprintf(
            'errors_%s.csv',
            pathinfo($dataImportMerchantFileTransfer->getFileInfoOrFail()->getOriginalFileNameOrFail(), PATHINFO_FILENAME),
        );
    }
}
