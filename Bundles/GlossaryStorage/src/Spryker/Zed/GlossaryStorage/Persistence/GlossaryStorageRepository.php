<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStoragePersistenceFactory getFactory()
 */
class GlossaryStorageRepository extends AbstractRepository implements GlossaryStorageRepositoryInterface
{
    /**
     * @param array $glossaryKeyIds
     *
     * @return \Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer[]
     */
    public function findGlossaryStorageEntityTransfer(array $glossaryKeyIds): array
    {
        if (!$glossaryKeyIds) {
            return [];
        }

        $query = $this->getFactory()
            ->createGlossaryStorageQuery()
            ->filterByFkGlossaryKey_In($glossaryKeyIds);

        return $this->buildQueryFromCriteria($query)->find();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array $ids
     *
     * @return \Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer[]
     */
    public function findFilteredGlossaryStorageEntities(FilterTransfer $filterTransfer, array $ids)
    {
        $query = $this->getFactory()->createGlossaryStorageQuery();

        if ($ids) {
            $query->filterByIdGlossaryStorage_In($ids);
        }

        return $this->buildQueryFromCriteria($query, $filterTransfer)->find();
    }
}
