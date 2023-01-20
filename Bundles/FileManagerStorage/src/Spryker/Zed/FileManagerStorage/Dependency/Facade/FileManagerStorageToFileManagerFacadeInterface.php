<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerStorage\Dependency\Facade;

use Generated\Shared\Transfer\FileCollectionTransfer;
use Generated\Shared\Transfer\FileCriteriaTransfer;

interface FileManagerStorageToFileManagerFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\FileCriteriaTransfer $fileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\FileCollectionTransfer
     */
    public function getFileCollection(FileCriteriaTransfer $fileCriteriaTransfer): FileCollectionTransfer;
}
