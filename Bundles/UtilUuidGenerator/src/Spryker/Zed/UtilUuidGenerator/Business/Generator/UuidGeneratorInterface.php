<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilUuidGenerator\Business\Generator;

interface UuidGeneratorInterface
{
    /**
     * @param string $tableName
     *
     * @return int
     */
    public function generate(string $tableName): int;
}
