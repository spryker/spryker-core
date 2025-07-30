<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantFile\Dependency\Service;

use Generated\Shared\Transfer\FileSystemContentTransfer;
use Generated\Shared\Transfer\FileSystemStreamTransfer;
use Spryker\Service\FileSystem\FileSystemServiceInterface;

class MerchantFileToFileSystemServiceBridge implements MerchantFileToFileSystemServiceInterface
{
    /**
     * @var \Spryker\Service\FileSystem\FileSystemServiceInterface
     */
    protected FileSystemServiceInterface $fileSystemService;

    /**
     * @param \Spryker\Service\FileSystem\FileSystemServiceInterface $fileSystemService
     */
    public function __construct($fileSystemService)
    {
        $this->fileSystemService = $fileSystemService;
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     *
     * @return mixed
     */
    public function readStream(FileSystemStreamTransfer $fileSystemStreamTransfer): mixed
    {
        return $this->fileSystemService->readStream($fileSystemStreamTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemContentTransfer $fileSystemContentTransfer
     *
     * @return void
     */
    public function write(FileSystemContentTransfer $fileSystemContentTransfer): void
    {
        $this->fileSystemService->write($fileSystemContentTransfer);
    }
}
