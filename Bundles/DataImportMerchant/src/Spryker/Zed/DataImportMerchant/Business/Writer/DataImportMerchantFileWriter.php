<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\DataImportMerchant\Business\Writer;

use DateTime;
use Generated\Shared\Transfer\DataImportMerchantFileInfoTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileTransfer;
use Generated\Shared\Transfer\FileSystemContentTransfer;
use Spryker\Zed\DataImportMerchant\DataImportMerchantConfig;
use Spryker\Zed\DataImportMerchant\Dependency\Service\DataImportMerchantToFileSystemServiceInterface;

class DataImportMerchantFileWriter implements DataImportMerchantFileWriterInterface
{
    /**
     * @var string
     */
    protected const UPLOAD_PATH = '{merchantReference}/{fileName}';

    /**
     * @var string
     */
    protected const CONFIG_KEY_CONTENT_TYPE = 'ContentType';

    /**
     * @param \Spryker\Zed\DataImportMerchant\Dependency\Service\DataImportMerchantToFileSystemServiceInterface $fileSystemService
     * @param \Spryker\Zed\DataImportMerchant\DataImportMerchantConfig $dataImportMerchantConfig
     */
    public function __construct(
        protected DataImportMerchantToFileSystemServiceInterface $fileSystemService,
        protected DataImportMerchantConfig $dataImportMerchantConfig
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileTransfer
     */
    public function writeFileToFileSystem(DataImportMerchantFileTransfer $dataImportMerchantFileTransfer): DataImportMerchantFileTransfer
    {
        $fileSystemContentTransfer = $this->createFileSystemContentTransfer($dataImportMerchantFileTransfer);
        $this->fileSystemService->write($fileSystemContentTransfer);

        return $this->mapFileSystemContentTransferToDataImportMerchantFileTransfer(
            $fileSystemContentTransfer,
            $dataImportMerchantFileTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     *
     * @return \Generated\Shared\Transfer\FileSystemContentTransfer
     */
    protected function createFileSystemContentTransfer(
        DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
    ): FileSystemContentTransfer {
        $dataImportMerchantFileInfoTransfer = $dataImportMerchantFileTransfer->getFileInfoOrFail();

        return (new FileSystemContentTransfer())
            ->setPath($this->buildUploadPath($dataImportMerchantFileTransfer))
            ->setFileSystemName($dataImportMerchantFileInfoTransfer->getFileSystemNameOrFail())
            ->setContent($dataImportMerchantFileInfoTransfer->getContentOrFail())
            ->setConfig($this->getFileSystemContentConfig($dataImportMerchantFileInfoTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemContentTransfer $fileSystemContentTransfer
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileTransfer
     */
    protected function mapFileSystemContentTransferToDataImportMerchantFileTransfer(
        FileSystemContentTransfer $fileSystemContentTransfer,
        DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
    ): DataImportMerchantFileTransfer {
        $dataImportMerchantFileTransfer->getFileInfoOrFail()
            ->setFileSystemName($fileSystemContentTransfer->getFileSystemName())
            ->setUploadedUrl($fileSystemContentTransfer->getPath())
            ->setContent(null);

        return $dataImportMerchantFileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileInfoTransfer $dataImportMerchantFileInfoTransfer
     *
     * @return array<string, mixed>
     */
    protected function getFileSystemContentConfig(DataImportMerchantFileInfoTransfer $dataImportMerchantFileInfoTransfer): array
    {
        return [
            static::CONFIG_KEY_CONTENT_TYPE => $dataImportMerchantFileInfoTransfer->getContentTypeOrFail(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     *
     * @return string
     */
    protected function buildUploadPath(DataImportMerchantFileTransfer $dataImportMerchantFileTransfer): string
    {
        return strtr(
            static::UPLOAD_PATH,
            [
                '{merchantReference}' => $dataImportMerchantFileTransfer->getMerchantReferenceOrFail(),
                '{fileName}' => $this->formatUploadFilename($dataImportMerchantFileTransfer),
            ],
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     *
     * @return string
     */
    protected function formatUploadFilename(DataImportMerchantFileTransfer $dataImportMerchantFileTransfer): string
    {
        $originalFilename = $dataImportMerchantFileTransfer->getFileInfoOrFail()->getOriginalFileNameOrFail();
        $pathInfo = pathinfo($originalFilename);
        $timestamp = (new DateTime())->format($this->dataImportMerchantConfig->getFileSuffixDateTimeFormat());

        return $pathInfo['filename']
            . '_' . $timestamp
            . (isset($pathInfo['extension']) ? '.' . $pathInfo['extension'] : '');
    }
}
