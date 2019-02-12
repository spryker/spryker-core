<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Uuid\Persistence;

use Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer;
use Generated\Shared\Transfer\UuidGeneratorReportTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\Uuid\Persistence\UuidPersistenceFactory getFactory()
 */
class UuidEntityManager extends AbstractEntityManager implements UuidEntityManagerInterface
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
    ): UuidGeneratorReportTransfer {
        $count = 0;
        $query = $this->getFactory()
            ->createQueryBuilder()
            ->buildQuery($uuidGeneratorConfigurationTransfer);

        do {
            /** @var \Propel\Runtime\Collection\ObjectCollection $entities */
            $entities = $query
                ->filterByUuid(null, Criteria::ISNULL)
                ->limit($batchSize)
                ->find();

            $count += $entities->count();

            foreach ($entities as $entity) {
                $entity->save();
            }
        } while ($entities->count());

        return (new UuidGeneratorReportTransfer())
            ->setTable($uuidGeneratorConfigurationTransfer->getTable())
            ->setCount($count);
    }
}
