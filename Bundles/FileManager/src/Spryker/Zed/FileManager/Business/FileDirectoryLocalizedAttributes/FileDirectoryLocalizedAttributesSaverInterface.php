<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\FileDirectoryLocalizedAttributes;

use Generated\Shared\Transfer\FileDirectoryTransfer;

interface FileDirectoryLocalizedAttributesSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     *
     * @return void
     */
    public function save(FileDirectoryTransfer $fileDirectoryTransfer);
}
