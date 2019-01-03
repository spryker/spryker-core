<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Uuid\Business\Generator;

use Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer;
use Generated\Shared\Transfer\UuidGeneratorReportTransfer;

interface UuidGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\UuidGeneratorReportTransfer
     */
    public function generate(UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer): UuidGeneratorReportTransfer;
}
