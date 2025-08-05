<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Reader;

use Generated\Shared\Transfer\FileTransfer;

interface FileReaderInterface
{
    public function findFileByIdFile(int $idFile): ?FileTransfer;
}
