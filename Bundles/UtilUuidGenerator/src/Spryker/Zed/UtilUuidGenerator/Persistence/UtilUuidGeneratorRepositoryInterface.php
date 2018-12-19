<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilUuidGenerator\Persistence;

use Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer;

/**
 * @method \Spryker\Zed\UtilUuidGenerator\Persistence\UtilUuidGeneratorPersistenceFactory getFactory()
 */
interface UtilUuidGeneratorRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer
     *
     * @return bool
     */
    public function hasUuidField(UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer): bool;
}
