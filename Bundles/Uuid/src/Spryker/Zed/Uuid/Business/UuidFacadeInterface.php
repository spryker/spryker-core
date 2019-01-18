<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Uuid\Business;

use Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer;
use Generated\Shared\Transfer\UuidGeneratorReportTransfer;

interface UuidFacadeInterface
{
    /**
     * Specification:
     * - Generates and fills uuid field for records where this field is null.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\UuidGeneratorReportTransfer
     */
    public function generateUuids(UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer): UuidGeneratorReportTransfer;
}
