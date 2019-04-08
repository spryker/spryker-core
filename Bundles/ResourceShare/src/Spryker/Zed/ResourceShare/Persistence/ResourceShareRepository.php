<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Persistence;

use Generated\Shared\Transfer\ResourceShareCriteriaTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Orm\Zed\ResourceShare\Persistence\SpyResourceShareQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ResourceShare\Persistence\ResourceSharePersistenceFactory getFactory()
 */
class ResourceShareRepository extends AbstractRepository implements ResourceShareRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ResourceShareCriteriaTransfer $resourceShareCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareTransfer|null
     */
    public function findResourceShareByCriteria(ResourceShareCriteriaTransfer $resourceShareCriteriaTransfer): ?ResourceShareTransfer
    {
        $resourceShareQuery = $this->getFactory()->createResourceSharePropelQuery();

        $resourceShareQuery = $this->filterResourceShareQuery(
            $resourceShareCriteriaTransfer,
            $resourceShareQuery
        );

        $resourceShareEntity = $resourceShareQuery->findOne();
        if (!$resourceShareEntity) {
            return null;
        }

        return $this->getFactory()
            ->createResourceShareMapper()
            ->mapResourceShareEntityToResourceShareTransfer($resourceShareEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareCriteriaTransfer $resourceShareCriteriaTransfer
     * @param \Orm\Zed\ResourceShare\Persistence\SpyResourceShareQuery $resourceShareQuery
     *
     * @return \Orm\Zed\ResourceShare\Persistence\SpyResourceShareQuery
     */
    protected function filterResourceShareQuery(
        ResourceShareCriteriaTransfer $resourceShareCriteriaTransfer,
        SpyResourceShareQuery $resourceShareQuery
    ): SpyResourceShareQuery {
        if ($resourceShareCriteriaTransfer->getIdResourceShare()) {
            $resourceShareQuery->filterByIdResourceShare($resourceShareCriteriaTransfer->getIdResourceShare());
        }

        if ($resourceShareCriteriaTransfer->getUuid()) {
            $resourceShareQuery->filterByUuid($resourceShareCriteriaTransfer->getUuid());
        }

        if ($resourceShareCriteriaTransfer->getResourceType()) {
            $resourceShareQuery->filterByResourceType($resourceShareCriteriaTransfer->getResourceType());
        }

        if ($resourceShareCriteriaTransfer->getResourceData()
            && $resourceShareCriteriaTransfer->isPropertyModified(ResourceShareCriteriaTransfer::RESOURCE_DATA)
        ) {
            $resourceShareQuery->filterByResourceData($resourceShareCriteriaTransfer->getResourceData());
        }

        if ($resourceShareCriteriaTransfer->getExpiryDate()) {
            $resourceShareQuery->filterByExpiryDate($resourceShareCriteriaTransfer->getExpiryDate());
        }

        if ($resourceShareCriteriaTransfer->getCustomerReference()) {
            $resourceShareQuery->filterByCustomerReference($resourceShareCriteriaTransfer->getCustomerReference());
        }

        return $resourceShareQuery;
    }
}
