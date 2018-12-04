<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilUuidGenerator\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\UtilUuidGenerator\Persistence\UtilUuidGeneratorPersistenceFactory getFactory()
 */
class UtilUuidGeneratorRepository extends AbstractRepository implements UtilUuidGeneratorRepositoryInterface
{
    protected const COLUMN_UUID = 'uuid';

    /**
     * @param string $tableName
     *
     * @return bool
     */
    public function hasUuidField(string $tableName): bool
    {
        $query = $this->getFactory()
            ->createQueryBuilder()
            ->buildQuery($tableName);

        return $query->getTableMap()->hasColumn(static::COLUMN_UUID);
    }
}
