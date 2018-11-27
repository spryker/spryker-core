<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilUuidGenerator\Business;

interface UtilUuidGeneratorFacadeInterface
{
    /**
     * Specification:
     * - Generates uuids for table.
     * - Returns count of updated records.
     *
     * @api
     *
     * @param string $tableName
     *
     * @return int
     */
    public function generateUuids(string $tableName): int;
}
