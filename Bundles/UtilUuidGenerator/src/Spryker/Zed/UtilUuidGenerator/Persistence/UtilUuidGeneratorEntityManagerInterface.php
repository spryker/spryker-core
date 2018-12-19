<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilUuidGenerator\Persistence;

use Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\EntityManagerInterface;

interface UtilUuidGeneratorEntityManagerInterface extends EntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer
     *
     * @return int
     */
    public function fillEmptyUuids(UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer): int;
}
