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
use Spryker\Zed\Synchronization\Persistence\Propel\Formatter\SynchronizationDataTransferObjectFormatter;

/**
 * @method \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStoragePersistenceFactory getFactory()
 */
class GlossaryStorageRepository extends AbstractRepository implements GlossaryStorageRepositoryInterface
{
    /**
     * @uses \Orm\Zed\GlossaryStorage\Persistence\Map\SpyGlossaryStorageTableMap::COL_ID_GLOSSARY_STORAGE
     */
    protected const COL_ID_GLOSSARY_STORAGE = 'spy_glossary_storage.id_glossary_storage';

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
     * @param int $offset
     * @param int $limit
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function findGlossaryStorageDataTransferByIds(int $offset, int $limit, array $ids): array
    {
        $filterTransfer = $this->createFilterTransfer($offset, $limit);
        $query = $this->getFactory()->createGlossaryStorageQuery();

        if ($ids) {
            $query->filterByIdGlossaryStorage_In($ids);
        }

        return $this->buildQueryFromCriteria($query, $filterTransfer)
            ->setFormatter(SynchronizationDataTransferObjectFormatter::class)
            ->find();
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
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\GlossaryKeyTransfer[]
     */
    public function findFilteredGlossaryKeyEntities(int $offset, int $limit): array
    {
        /** @var \Propel\Runtime\ActiveQuery\ModelCriteria $query */
        $query = $this->getFactory()
            ->getGlossaryKeyQuery()
            ->setLimit($limit)
            ->setOffset($offset);

        $glossaryKeyEntities = $this->buildQueryFromCriteria($query)
            ->setFormatter(ModelCriteria::FORMAT_OBJECT)
            ->find()
            ->getData();

        return $this->getFactory()->createGlossaryStorageMapper()->hydrateGlossaryKeyTransfer($glossaryKeyEntities);
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\FilterTransfer
     */
    protected function createFilterTransfer(int $offset, int $limit): FilterTransfer
    {
        return (new FilterTransfer())
            ->setOrderBy(static::COL_ID_GLOSSARY_STORAGE)
            ->setOffset($offset)
            ->setLimit($limit);
    }
}
