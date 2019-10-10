<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

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

    /**
     * @param array $glossaryKeyIds
     *
     * @return \Generated\Shared\Transfer\SpyGlossaryTranslationEntityTransfer[]
     */
    public function findGlossaryTranslationEntityTransfer(array $glossaryKeyIds): array
    {
        if (!$glossaryKeyIds) {
            return [];
        }

        $query = $this->getFactory()
            ->getGlossaryTranslationQuery()
            ->leftJoinWithGlossaryKey()
            ->joinWithLocale()
            ->addAnd('fk_glossary_key', $glossaryKeyIds, Criteria::IN);

        return $this->buildQueryFromCriteria($query)->find();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SpyGlossaryKeyEntityTransfer[]
     */
    public function findFilteredGlossaryKeyEntityTransfers(FilterTransfer $filterTransfer): array
    {
        $query = $this->getFactory()
            ->getGlossaryKeyQuery()
            ->setLimit($filterTransfer->getLimit())
            ->setOffset($filterTransfer->getOffset());

        return $this->buildQueryFromCriteria($query)->find();
    }
}
