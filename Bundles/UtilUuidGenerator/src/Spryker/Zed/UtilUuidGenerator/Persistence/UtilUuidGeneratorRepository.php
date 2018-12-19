<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilUuidGenerator\Persistence;

use Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\UtilUuidGenerator\Persistence\UtilUuidGeneratorPersistenceFactory getFactory()
 */
class UtilUuidGeneratorRepository extends AbstractRepository implements UtilUuidGeneratorRepositoryInterface
{
    protected const COLUMN_UUID = 'uuid';

    /**
     * @param \Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer
     *
     * @return bool
     */
    public function hasUuidField(UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer): bool
    {
        $query = $this->getFactory()
            ->createQueryBuilder()
            ->buildQuery(
                $uuidGeneratorConfigurationTransfer->getModule(),
                $uuidGeneratorConfigurationTransfer->getTable()
            );

        return $query->getTableMap()->hasColumn(static::COLUMN_UUID);
    }
}
