<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStoragePersistenceFactory getFactory()
 */
class GlossaryStorageRepository extends AbstractRepository implements GlossaryStorageRepositoryInterface
{
    /**
     * @param int[] $glossaryKeyIds
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
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\GlossaryStorageTransfer[]
     */
    public function findFilteredGlossaryStorageEntities(FilterTransfer $filterTransfer, array $ids): array
    {
        $query = $this->getFactory()->createGlossaryStorageQuery();

        if ($ids) {
            $query->filterByIdGlossaryStorage_In($ids);
        }

        $glossaryStorageEntities = $this->buildQueryFromCriteria($query, $filterTransfer)
            ->setFormatter(ModelCriteria::FORMAT_OBJECT)
            ->find()
            ->getData();

        return $this->getFactory()->createGlossaryStorageMapper()->hydrateGlossaryStorageTransfer($glossaryStorageEntities);
    }

    /**
     * @param int[] $glossaryKeyIds
     *
     * @return \Generated\Shared\Transfer\SpyGlossaryTranslationEntityTransfer[]
     */
    public function findGlossaryTranslationEntityTransfer(array $glossaryKeyIds): array
    {
        if (!$glossaryKeyIds) {
            return [];
        }

        /** @var \Propel\Runtime\ActiveQuery\ModelCriteria $query */
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
     * @return \Generated\Shared\Transfer\GlossaryKeyTransfer[]
     */
    public function findFilteredGlossaryKeyEntities(FilterTransfer $filterTransfer): array
    {
        /** @var \Propel\Runtime\ActiveQuery\ModelCriteria $query */
        $query = $this->getFactory()
            ->getGlossaryKeyQuery()
            ->setLimit($filterTransfer->getLimit())
            ->setOffset($filterTransfer->getOffset());

        $glossaryKeyEntityTransfers = $this->buildQueryFromCriteria($query)
            ->setFormatter(ModelCriteria::FORMAT_OBJECT)
            ->find()
            ->getData();

        return $this->getFactory()->createGlossaryStorageMapper()->hydrateGlossaryKeyTransfer($glossaryKeyEntityTransfers);
    }
}
