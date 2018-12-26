<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Uuid\Persistence;

use Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer;

/**
 * @method \Spryker\Zed\Uuid\Persistence\UuidPersistenceFactory getFactory()
 */
interface UuidRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer
     *
     * @return bool
     */
    public function isUuidColumnDefinedInTable(UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer): bool;
}
