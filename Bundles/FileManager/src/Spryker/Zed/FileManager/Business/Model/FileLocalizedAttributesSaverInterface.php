<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Generated\Shared\Transfer\FileManagerSaveRequestTransfer;
use Orm\Zed\FileManager\Persistence\SpyFile;

interface FileLocalizedAttributesSaverInterface
{
    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $fileEntity
     * @param \Generated\Shared\Transfer\FileManagerSaveRequestTransfer $fileManagerSaveRequestTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function saveLocalizedFileAttributes(SpyFile $fileEntity, FileManagerSaveRequestTransfer $fileManagerSaveRequestTransfer);
}
