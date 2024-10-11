<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerExtension\Dependency\Plugin;

use Generated\Shared\Transfer\FileManagerDataCollectionTransfer;

/**
 * Provides an ability to expand `FileManagerDataCollectionTransfer` with additional data.
 *
 * Implement this plugin interface to expand `FileManagerDataCollectionTransfer` before the file manager data is saved to the persistence.
 */
interface FileManagerDataCollectionExpanderPreSavePluginInterface
{
    /**
     * Specification:
     * - Expands `FileManagerDataCollectionTransfer` with an additional data before save.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileManagerDataCollectionTransfer $fileManagerDataCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\FileManagerDataCollectionTransfer
     */
    public function expand(FileManagerDataCollectionTransfer $fileManagerDataCollectionTransfer): FileManagerDataCollectionTransfer;
}
