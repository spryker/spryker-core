<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\ReferenceGenerator;

use Generated\Shared\Transfer\FileTransfer;

interface FileReferenceGeneratorInterface
{
    public function generateFileReference(FileTransfer $fileTransfer): string;
}
