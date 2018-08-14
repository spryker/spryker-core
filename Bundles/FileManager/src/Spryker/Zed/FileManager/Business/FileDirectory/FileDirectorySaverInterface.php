<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\FileDirectory;

use Generated\Shared\Transfer\FileDirectoryTransfer;

interface FileDirectorySaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     *
     * @return int
     */
    public function save(FileDirectoryTransfer $fileDirectoryTransfer);
}
