<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantFile\Business\MerchantFile\Reader;

use Generated\Shared\Transfer\FileSystemStreamTransfer;
use Generated\Shared\Transfer\MerchantFileCriteriaTransfer;
use Generated\Shared\Transfer\MerchantFileTransfer;
use Spryker\Zed\MerchantFile\Business\Exception\MerchantFileNotFoundException;
use Spryker\Zed\MerchantFile\Dependency\Service\MerchantFileToFileSystemServiceInterface;
use Spryker\Zed\MerchantFile\MerchantFileConfig;
use Spryker\Zed\MerchantFile\Persistence\MerchantFileRepositoryInterface;

class MerchantFileReader implements MerchantFileReaderInterface
{
    /**
     * @param \Spryker\Zed\MerchantFile\Persistence\MerchantFileRepositoryInterface $MerchantFileRepository
     * @param \Spryker\Zed\MerchantFile\MerchantFileConfig $config
     * @param \Spryker\Zed\MerchantFile\Dependency\Service\MerchantFileToFileSystemServiceInterface $fileSystemService
     */
    public function __construct(
        protected MerchantFileRepositoryInterface $MerchantFileRepository,
        protected MerchantFileConfig $config,
        protected MerchantFileToFileSystemServiceInterface $fileSystemService
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer
     *
     * @throws \Spryker\Zed\MerchantFile\Business\Exception\MerchantFileNotFoundException
     *
     * @return resource
     */
    public function readMerchantFileStream(MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer)
    {
        $merchantFileCollectionTransfer = $this->MerchantFileRepository->getMerchantFileCollection(
            $merchantFileCriteriaTransfer,
        );

        if (!$merchantFileCollectionTransfer->getMerchantFiles()->count()) {
            throw new MerchantFileNotFoundException();
        }

        /** @var \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer */
        $merchantFileTransfer = $merchantFileCollectionTransfer->getMerchantFiles()->getIterator()->current();

        return $this->fileSystemService->readStream(
            $this->createFileSystemStreamTransfer($merchantFileTransfer),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     *
     * @return \Generated\Shared\Transfer\FileSystemStreamTransfer
     */
    protected function createFileSystemStreamTransfer(
        MerchantFileTransfer $merchantFileTransfer
    ): FileSystemStreamTransfer {
        $fileSystemName = $merchantFileTransfer->getFileSystemName() ?? $this->config->getFileSystemName();

        return (new FileSystemStreamTransfer())
            ->setFileSystemName($fileSystemName)
            ->setPath($merchantFileTransfer->getUploadedUrl());
    }
}
