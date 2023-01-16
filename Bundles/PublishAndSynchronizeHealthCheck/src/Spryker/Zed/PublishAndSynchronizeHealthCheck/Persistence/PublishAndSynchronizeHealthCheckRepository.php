<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheck\Persistence;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckCollectionTransfer;
use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckCriteriaTransfer;
use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer;
use Orm\Zed\PublishAndSynchronizeHealthCheck\Persistence\SpyPublishAndSynchronizeHealthCheckQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheck\Persistence\PublishAndSynchronizeHealthCheckPersistenceFactory getFactory()
 */
class PublishAndSynchronizeHealthCheckRepository extends AbstractRepository implements PublishAndSynchronizeHealthCheckRepositoryInterface
{
    /**
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer|null
     */
    public function findPublishAndSynchronizeHealthCheckByKey(string $key): ?PublishAndSynchronizeHealthCheckTransfer
    {
        $publishAndSynchronizeHealthCheckEntity = $this->getFactory()
            ->createPublishAndSynchronizeHealthCheckQuery()
            ->filterByHealthCheckKey($key)
            ->findOne();

        if (!$publishAndSynchronizeHealthCheckEntity) {
            return null;
        }

        return (new PublishAndSynchronizeHealthCheckTransfer())->fromArray($publishAndSynchronizeHealthCheckEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckCriteriaTransfer $publishAndSynchronizeHealthCheckCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckCollectionTransfer
     */
    public function getPublishAndSynchronizeHealthCheckCollection(
        PublishAndSynchronizeHealthCheckCriteriaTransfer $publishAndSynchronizeHealthCheckCriteriaTransfer
    ): PublishAndSynchronizeHealthCheckCollectionTransfer {
        $publishAndSynchronizeHealthCheckCollectionTransfer = new PublishAndSynchronizeHealthCheckCollectionTransfer();
        $publishAndSynchronizeHealthCheckQuery = $this->getFactory()->createPublishAndSynchronizeHealthCheckQuery();

        $publishAndSynchronizeHealthCheckQuery = $this->applyPublishAndSynchronizeHealthCheckFilters(
            $publishAndSynchronizeHealthCheckQuery,
            $publishAndSynchronizeHealthCheckCriteriaTransfer,
        );

        $paginationTransfer = $publishAndSynchronizeHealthCheckCriteriaTransfer->getPagination();
        if ($paginationTransfer) {
            $publishAndSynchronizeHealthCheckQuery = $this->applyPublishAndSynchronizeHealthCheckPagination(
                $publishAndSynchronizeHealthCheckQuery,
                $paginationTransfer,
            );
            $publishAndSynchronizeHealthCheckCollectionTransfer->setPagination($paginationTransfer);
        }

        return $this->getFactory()
            ->createPublishAndSynchronizeHealthCheckMapper()
            ->mapPublishAndSynchronizeHealthCheckEntitiesToPublishAndSynchronizeHealthCheckCollectionTransfer(
                $publishAndSynchronizeHealthCheckQuery->find(),
                $publishAndSynchronizeHealthCheckCollectionTransfer,
            );
    }

    /**
     * @param \Orm\Zed\PublishAndSynchronizeHealthCheck\Persistence\SpyPublishAndSynchronizeHealthCheckQuery $publishAndSynchronizeHealthCheckQuery
     * @param \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckCriteriaTransfer $publishAndSynchronizeHealthCheckCriteriaTransfer
     *
     * @return \Orm\Zed\PublishAndSynchronizeHealthCheck\Persistence\SpyPublishAndSynchronizeHealthCheckQuery
     */
    protected function applyPublishAndSynchronizeHealthCheckFilters(
        SpyPublishAndSynchronizeHealthCheckQuery $publishAndSynchronizeHealthCheckQuery,
        PublishAndSynchronizeHealthCheckCriteriaTransfer $publishAndSynchronizeHealthCheckCriteriaTransfer
    ): SpyPublishAndSynchronizeHealthCheckQuery {
        $publishAndSynchronizeHealthCheckConditionsTransfer = $publishAndSynchronizeHealthCheckCriteriaTransfer->getPublishAndSynchronizeHealthCheckConditions();

        if (!$publishAndSynchronizeHealthCheckConditionsTransfer) {
            return $publishAndSynchronizeHealthCheckQuery;
        }

        if ($publishAndSynchronizeHealthCheckConditionsTransfer->getPublishAndSynchronizeHealthCheckIds()) {
            $publishAndSynchronizeHealthCheckQuery->filterByIdPublishAndSynchronizeHealthCheck_In(
                $publishAndSynchronizeHealthCheckConditionsTransfer->getPublishAndSynchronizeHealthCheckIds(),
            );
        }

        return $publishAndSynchronizeHealthCheckQuery;
    }

    /**
     * @param \Orm\Zed\PublishAndSynchronizeHealthCheck\Persistence\SpyPublishAndSynchronizeHealthCheckQuery $publishAndSynchronizeHealthCheckQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Orm\Zed\PublishAndSynchronizeHealthCheck\Persistence\SpyPublishAndSynchronizeHealthCheckQuery
     */
    protected function applyPublishAndSynchronizeHealthCheckPagination(
        SpyPublishAndSynchronizeHealthCheckQuery $publishAndSynchronizeHealthCheckQuery,
        PaginationTransfer $paginationTransfer
    ): SpyPublishAndSynchronizeHealthCheckQuery {
        $paginationTransfer->setNbResults($publishAndSynchronizeHealthCheckQuery->count());

        if ($paginationTransfer->getLimit() !== null && $paginationTransfer->getOffset() !== null) {
            return $publishAndSynchronizeHealthCheckQuery
                ->limit($paginationTransfer->getLimitOrFail())
                ->offset($paginationTransfer->getOffsetOrFail());
        }

        return $publishAndSynchronizeHealthCheckQuery;
    }
}
