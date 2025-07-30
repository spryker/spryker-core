<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantFile\Business\File\Writer;

use DateTime;
use Generated\Shared\Transfer\FileSystemContentTransfer;
use Generated\Shared\Transfer\MerchantFileTransfer;
use Spryker\Zed\MerchantFile\Dependency\Service\MerchantFileToFileSystemServiceInterface;
use Spryker\Zed\MerchantFile\MerchantFileConfig;

class FileWriter implements FileWriterInterface
{
    /**
     * @var string
     */
    protected const UPLOAD_PATH = '{merchantId}/{fileType}/{fileName}';

    /**
     * @var string
     */
    protected const CONFIG_KEY_CONTENT_TYPE = 'ContentType';

    /**
     * @param \Spryker\Zed\MerchantFile\Dependency\Service\MerchantFileToFileSystemServiceInterface $fileSystemService
     * @param \Spryker\Zed\MerchantFile\MerchantFileConfig $config
     */
    public function __construct(
        protected MerchantFileToFileSystemServiceInterface $fileSystemService,
        protected MerchantFileConfig $config
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileTransfer
     */
    public function writeMerchantFile(MerchantFileTransfer $merchantFileTransfer): MerchantFileTransfer
    {
        $fileSystemContentTransfer = $this->buildFileSystemContentTransfer($merchantFileTransfer);
        $this->fileSystemService->write($fileSystemContentTransfer);

        return $this->mapUploadedMerchantFileData($merchantFileTransfer, $fileSystemContentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     *
     * @return \Generated\Shared\Transfer\FileSystemContentTransfer
     */
    protected function buildFileSystemContentTransfer(
        MerchantFileTransfer $merchantFileTransfer
    ): FileSystemContentTransfer {
        $fileSystemName = $merchantFileTransfer->getFileSystemName() ?? $this->config->getFileSystemName();
        $uploadPath = $this->buildUploadPath($merchantFileTransfer);

        return (new FileSystemContentTransfer())
            ->setPath($uploadPath)
            ->setFileSystemName($fileSystemName)
            ->setContent($merchantFileTransfer->getContent())
            ->setConfig($this->getFileSystemContentConfig($merchantFileTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     * @param \Generated\Shared\Transfer\FileSystemContentTransfer $fileSystemContentTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileTransfer
     */
    protected function mapUploadedMerchantFileData(
        MerchantFileTransfer $merchantFileTransfer,
        FileSystemContentTransfer $fileSystemContentTransfer
    ): MerchantFileTransfer {
        $merchantFileTransfer->setFileSystemName($fileSystemContentTransfer->getFileSystemName());
        $merchantFileTransfer->setUploadedUrl($fileSystemContentTransfer->getPath());

        return $merchantFileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     *
     * @return array<string, mixed>
     */
    protected function getFileSystemContentConfig(MerchantFileTransfer $merchantFileTransfer): array
    {
        return [
            static::CONFIG_KEY_CONTENT_TYPE => $merchantFileTransfer->getContentType(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     *
     * @return string
     */
    protected function buildUploadPath(MerchantFileTransfer $merchantFileTransfer): string
    {
        return strtr(
            static::UPLOAD_PATH,
            [
                '{merchantId}' => $merchantFileTransfer->getFkMerchant(),
                '{fileType}' => $merchantFileTransfer->getType(),
                '{fileName}' => $this->formatUploadFilename($merchantFileTransfer->getOriginalFileNameOrFail()),
            ],
        );
    }

    /**
     * @param string $originalFilename
     *
     * @return string
     */
    protected function formatUploadFilename(string $originalFilename): string
    {
        $pathInfo = pathinfo($originalFilename);
        $timestamp = (new DateTime())->format($this->config->getFileSuffixDateTimeFormat());

        return $pathInfo['filename']
            . '_' . $timestamp
            . (isset($pathInfo['extension']) ? '.' . $pathInfo['extension'] : '');
    }
}
