<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\FileLocalizedAttributes;

use Generated\Shared\Transfer\FileManagerDataTransfer;

interface FileLocalizedAttributesSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     *
     * @return void
     */
    public function save(FileManagerDataTransfer $fileManagerDataTransfer);
}
