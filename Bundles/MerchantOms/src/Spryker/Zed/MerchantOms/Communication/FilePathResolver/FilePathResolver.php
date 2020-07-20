<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Communication\FilePathResolver;

use Generated\Shared\Transfer\FilePathResolverResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;

class FilePathResolver implements FilePathResolverInterface
{
    /**
     * @param string $filePath
     *
     * @return \Generated\Shared\Transfer\FilePathResolverResponseTransfer
     */
    public function resolveFilePath(string $filePath): FilePathResolverResponseTransfer
    {
        $filePaths = [
            $filePath,
            APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . $filePath,
        ];

        foreach ($filePaths as $filePath) {
            if (is_file($filePath) && is_readable($filePath)) {
                return (new FilePathResolverResponseTransfer())
                    ->setIsSuccessful(true)
                    ->setFilePath($filePath);
            }
        }

        return (new FilePathResolverResponseTransfer())
            ->setIsSuccessful(false)
            ->setMessage(
                (new MessageTransfer())->setMessage(sprintf('File "%s" does not exist or is unreadable.', $filePath))
            );
    }
}
