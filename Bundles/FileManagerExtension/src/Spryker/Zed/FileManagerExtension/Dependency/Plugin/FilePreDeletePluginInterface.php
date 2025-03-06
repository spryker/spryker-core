<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerExtension\Dependency\Plugin;

use Generated\Shared\Transfer\FileCollectionTransfer;

/**
 * Implement this plugin interface to add logic before a file is deleted.
 */
interface FilePreDeletePluginInterface
{
    /**
     * Specification:
     * - Executed before files from the collection are deleted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileCollectionTransfer $fileCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\FileCollectionTransfer
     */
    public function preDelete(FileCollectionTransfer $fileCollectionTransfer): FileCollectionTransfer;
}
