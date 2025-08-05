<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Formatter;

interface FileSizeFormatterInterface
{
    public function formatFileSize(int $fileSize, int $numberOfDecimals): string;
}
