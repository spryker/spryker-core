<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FileManager;

use Generated\Shared\Transfer\ReadFileTransfer;

interface FileManagerClientInterface
{
    /**
     * Specification:
     * - Returns required file and file info transfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReadFileTransfer $readFileTransfer
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function readFile(ReadFileTransfer $readFileTransfer);
}
