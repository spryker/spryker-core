<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\DataImportMerchantPortalGui\Dependency\Service;

use Generated\Shared\Transfer\FileSystemStreamTransfer;

interface DataImportMerchantPortalGuiToFileSystemServiceInterface
{
    /**
     * @param \Generated\Shared\Transfer\FileSystemStreamTransfer $fileSystemStreamTransfer
     *
     * @return mixed
     */
    public function readStream(FileSystemStreamTransfer $fileSystemStreamTransfer);
}
