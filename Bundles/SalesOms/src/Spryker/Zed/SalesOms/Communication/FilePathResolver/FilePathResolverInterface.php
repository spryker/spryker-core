<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOms\Communication\FilePathResolver;

use Generated\Shared\Transfer\FilePathResolverResponseTransfer;

interface FilePathResolverInterface
{
    /**
     * @param string $filePath
     *
     * @return \Generated\Shared\Transfer\FilePathResolverResponseTransfer
     */
    public function resolveFilePath(string $filePath): FilePathResolverResponseTransfer;
}
