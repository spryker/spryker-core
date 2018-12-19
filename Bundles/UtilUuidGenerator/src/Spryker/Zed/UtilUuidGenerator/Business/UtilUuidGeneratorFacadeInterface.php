<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilUuidGenerator\Business;

use Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer;

interface UtilUuidGeneratorFacadeInterface
{
    /**
     * Specification:
     * - Generate and fills uuid field for records where this field is null.
     * - Returns count of updated records.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer
     *
     * @return int
     */
    public function generateUuids(UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer): int;
}
