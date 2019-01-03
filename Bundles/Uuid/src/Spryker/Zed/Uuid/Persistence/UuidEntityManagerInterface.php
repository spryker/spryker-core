<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Uuid\Persistence;

use Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer;
use Generated\Shared\Transfer\UuidGeneratorReportTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\EntityManagerInterface;

interface UuidEntityManagerInterface extends EntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer
     * @param int $batchSize
     *
     * @return \Generated\Shared\Transfer\UuidGeneratorReportTransfer
     */
    public function fillEmptyUuids(
        UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer,
        int $batchSize
    ): UuidGeneratorReportTransfer;
}
