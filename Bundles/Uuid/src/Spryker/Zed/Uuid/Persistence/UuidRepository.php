<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Uuid\Persistence;

use Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Uuid\Persistence\UuidPersistenceFactory getFactory()
 */
class UuidRepository extends AbstractRepository implements UuidRepositoryInterface
{
    protected const COLUMN_UUID = 'uuid';

    /**
     * @param \Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer
     *
     * @return bool
     */
    public function isUuidColumnDefinedInTable(UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer): bool
    {
        $query = $this->getFactory()
            ->createQueryBuilder()
            ->buildQuery($uuidGeneratorConfigurationTransfer);

        return $query->getTableMap()->hasColumn(static::COLUMN_UUID);
    }
}
