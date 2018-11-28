<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilUuidGenerator\Persistence;

/**
 * @method \Spryker\Zed\UtilUuidGenerator\Persistence\UtilUuidGeneratorPersistenceFactory getFactory()
 */
interface UtilUuidGeneratorRepositoryInterface
{
    /**
     * @param string $tableName
     *
     * @return bool
     */
    public function hasQueryUuidField(string $tableName): bool;
}
