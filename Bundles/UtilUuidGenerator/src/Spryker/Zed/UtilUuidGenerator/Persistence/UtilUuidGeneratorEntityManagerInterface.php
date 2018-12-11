<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilUuidGenerator\Persistence;

use Spryker\Zed\Kernel\Persistence\EntityManager\EntityManagerInterface;

interface UtilUuidGeneratorEntityManagerInterface extends EntityManagerInterface
{
    /**
     * @param string $tableAlias
     *
     * @return int
     */
    public function fillEmptyUuids(string $tableAlias): int;
}
