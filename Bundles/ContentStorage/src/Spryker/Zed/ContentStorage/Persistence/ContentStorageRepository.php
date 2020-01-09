<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentStorage\Persistence;

use Generated\Shared\Transfer\ContentStorageTransfer;
use Generated\Shared\Transfer\ContentTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Propel\Runtime\Formatter\ObjectFormatter;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\ContentStorage\Persistence\ContentStoragePersistenceFactory getFactory()
 */
class ContentStorageRepository extends AbstractRepository implements ContentStorageRepositoryInterface
{
    /**
     * @param int[] $contentIds
     *
     * @return \Generated\Shared\Transfer\ContentStorageTransfer[]
     */
    public function findContentStorageByContentIds(array $contentIds): array
    {
        $contentStorageTransfers = [];
        $contentStorageEntities = $this->getFactory()
            ->createContentStorageQuery()
            ->filterByFkContent($contentIds, Criteria::IN)
            ->find();

        foreach ($contentStorageEntities as $contentStorageEntity) {
            $contentStorageTransfers[] = $this->getFactory()
                ->createContentStorageMapper()
                ->mapContentStorageEntityToTransfer($contentStorageEntity, new ContentStorageTransfer());
        }

        return $contentStorageTransfers;
    }

    /**
     * @param array $contentIds
     *
     * @return \Generated\Shared\Transfer\ContentTransfer[]
     */
    public function findContentByIds(array $contentIds): array
    {
        $contentTransfers = [];
        $contentEntities = $this->getFactory()
            ->getContentQuery()
            ->filterByIdContent($contentIds, Criteria::IN)
            ->joinWithSpyContentLocalized()
            ->find();

        foreach ($contentEntities as $contentEntity) {
            $contentTransfers[] = $this->getFactory()
                ->createContentStorageMapper()
                ->mapContentEntityToTransfer($contentEntity, new ContentTransfer());
        }

        return $contentTransfers;
    }

    /**
     * @return \Generated\Shared\Transfer\ContentStorageTransfer[]
     */
    public function findAllContentStorage(): array
    {
        $contentStorageTransfers = [];
        $contentStorageEntities = $this->getFactory()
            ->createContentStorageQuery()
            ->find();

        foreach ($contentStorageEntities as $contentStorageEntity) {
            $contentStorageTransfers[] = $this->getFactory()
                ->createContentStorageMapper()
                ->mapContentStorageEntityToTransfer($contentStorageEntity, new ContentStorageTransfer());
        }

        return $contentStorageTransfers;
    }

    /**
     * @module Content
     *
     * @deprecated Will be removed without replacement.
     *
     * @param array $contentIds
     *
     * @return \Generated\Shared\Transfer\SpyContentEntityTransfer[]
     */
    public function findContentByContentIds(array $contentIds): array
    {
        $query = $this->getFactory()->getContentQuery();

        if ($contentIds !== []) {
            $query->filterByIdContent_In($contentIds);
        }

        return $this->buildQueryFromCriteria($query)->find();
    }

    /**
     * @module Content
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\ContentTransfer[]
     */
    public function getContentTransfersByFilter(FilterTransfer $filterTransfer): array
    {
        $query = $this->getFactory()
            ->getContentQuery();

        $contentEntityCollection = $this->buildQueryFromCriteria($query, $filterTransfer)
            ->setFormatter(ObjectFormatter::class)
            ->find();

        return $this->getFactory()
            ->createContentStorageMapper()
            ->mapContentEntityCollectionToContentTransfers($contentEntityCollection);
    }
}
