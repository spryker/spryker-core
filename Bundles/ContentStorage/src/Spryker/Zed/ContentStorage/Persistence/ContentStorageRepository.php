<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentStorage\Persistence;

use Generated\Shared\Transfer\ContentStorageTransfer;
use Generated\Shared\Transfer\ContentTransfer;
use Generated\Shared\Transfer\FilterTransfer;
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
        $contentStorageEntities = $this->getFactory()
            ->createContentStorageQuery()
            ->filterByFkContent($contentIds, Criteria::IN)
            ->find();

        return $this->mapContentStorageEntityCollectionToContentStorageTransferCollection($contentStorageEntities);
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
        $contentStorageEntities = $this->getFactory()
            ->createContentStorageQuery()
            ->find();

        return $this->mapContentStorageEntityCollectionToContentStorageTransferCollection($contentStorageEntities);
    }

    /**
     * @deprecated Use `ContentStorageRepository::getContentCollectionByFilter()` instead.
     *
     * @see \Spryker\Zed\ContentStorage\Persistence\ContentStorageRepository::getContentCollectionByFilter()
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
     * @return \Generated\Shared\Transfer\ContentStorageTransfer[]
     */
    public function getContentCollectionByFilter(FilterTransfer $filterTransfer): array
    {
        $query = $this->getFactory()
            ->getContentQuery();

        $contentStorageEntities = $this->buildQueryFromCriteria($query, $filterTransfer)->find();

        return $this->mapContentStorageEntityCollectionToContentStorageTransferCollection($contentStorageEntities);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyContentEntityTransfer[] $contentStorageEntities
     *
     * @return \Generated\Shared\Transfer\ContentStorageTransfer[]
     */
    protected function mapContentStorageEntityCollectionToContentStorageTransferCollection(array $contentStorageEntities): array
    {
        $contentStorageMapper = $this->getFactory()->createContentStorageMapper();
        $contentStorageTransfers = [];

        foreach ($contentStorageEntities as $contentStorageEntity) {
            $contentStorageTransfers[] = $contentStorageMapper->mapContentStorageEntityToTransfer(
                $contentStorageEntity,
                new ContentStorageTransfer()
            );
        }

        return $contentStorageTransfers;
    }
}
