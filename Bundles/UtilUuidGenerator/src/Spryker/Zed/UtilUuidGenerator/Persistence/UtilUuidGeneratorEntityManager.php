<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilUuidGenerator\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\UtilUuidGenerator\Persistence\UtilUuidGeneratorPersistenceFactory getFactory()
 */
class UtilUuidGeneratorEntityManager extends AbstractEntityManager implements UtilUuidGeneratorEntityManagerInterface
{
    protected const BATCH_SIZE = 200;

    /**
     * @param string $tableAlias
     *
     * @return int
     */
    public function fillEmptyUuids(string $tableAlias): int
    {
        $count = 0;
        $query = $this->getFactory()
            ->createQueryBuilder()
            ->buildQuery($tableAlias);

        do {
            /** @var \Propel\Runtime\Collection\ObjectCollection $entities */
            $entities = $query
                ->filterByUuid(null, Criteria::ISNULL)
                ->limit(static::BATCH_SIZE)
                ->find();

            $count += $entities->count();

            foreach ($entities as $entity) {
                $entity->save();
            }
        } while ($entities->count());

        return $count;
    }
}
