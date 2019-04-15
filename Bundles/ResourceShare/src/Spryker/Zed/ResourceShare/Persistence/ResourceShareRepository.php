<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Persistence;

use Generated\Shared\Transfer\ResourceShareTransfer;
use Orm\Zed\ResourceShare\Persistence\SpyResourceShareQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ResourceShare\Persistence\ResourceSharePersistenceFactory getFactory()
 */
class ResourceShareRepository extends AbstractRepository implements ResourceShareRepositoryInterface
{
    /**
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\ResourceShareTransfer|null
     */
    public function findResourceShareByUuid(string $uuid): ?ResourceShareTransfer
    {
        $resourceShareEntity = $this->getFactory()
            ->createResourceSharePropelQuery()
            ->filterByUuid($uuid)
            ->findOne();

        if (!$resourceShareEntity) {
            return null;
        }

        return $this->getFactory()
            ->createResourceShareMapper()
            ->mapResourceShareEntityToResourceShareTransfer($resourceShareEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareTransfer|null
     */
    public function findResourceShare(ResourceShareTransfer $resourceShareTransfer): ?ResourceShareTransfer
    {
        $resourceShareQuery = $this->getFactory()->createResourceSharePropelQuery();

        $resourceShareQuery = $this->setResourceShareQueryFilters(
            $resourceShareTransfer,
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
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     * @param \Orm\Zed\ResourceShare\Persistence\SpyResourceShareQuery $resourceShareQuery
     *
     * @return \Orm\Zed\ResourceShare\Persistence\SpyResourceShareQuery
     */
    protected function setResourceShareQueryFilters(
        ResourceShareTransfer $resourceShareTransfer,
        SpyResourceShareQuery $resourceShareQuery
    ): SpyResourceShareQuery {
        $resourceShareQuery = $this->setResourceShareQueryIdentityFilters($resourceShareTransfer, $resourceShareQuery);
        $resourceShareQuery = $this->setResourceShareQueryResourceFilters($resourceShareTransfer, $resourceShareQuery);

        return $resourceShareQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     * @param \Orm\Zed\ResourceShare\Persistence\SpyResourceShareQuery $resourceShareQuery
     *
     * @return \Orm\Zed\ResourceShare\Persistence\SpyResourceShareQuery
     */
    protected function setResourceShareQueryResourceFilters(
        ResourceShareTransfer $resourceShareTransfer,
        SpyResourceShareQuery $resourceShareQuery
    ): SpyResourceShareQuery {
        if ($resourceShareTransfer->getResourceType()) {
            $resourceShareQuery->filterByResourceType($resourceShareTransfer->getResourceType());
        }

        if ($resourceShareTransfer->getResourceData()
            && $resourceShareTransfer->isPropertyModified(ResourceShareTransfer::RESOURCE_DATA)
        ) {
            $resourceShareQuery->filterByResourceData($resourceShareTransfer->getResourceData());
        }

        if ($resourceShareTransfer->getExpiryDate()) {
            $resourceShareQuery->filterByExpiryDate($resourceShareTransfer->getExpiryDate());
        }

        return $resourceShareQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     * @param \Orm\Zed\ResourceShare\Persistence\SpyResourceShareQuery $resourceShareQuery
     *
     * @return \Orm\Zed\ResourceShare\Persistence\SpyResourceShareQuery
     */
    protected function setResourceShareQueryIdentityFilters(
        ResourceShareTransfer $resourceShareTransfer,
        SpyResourceShareQuery $resourceShareQuery
    ): SpyResourceShareQuery {
        if ($resourceShareTransfer->getIdResourceShare()) {
            $resourceShareQuery->filterByIdResourceShare($resourceShareTransfer->getIdResourceShare());
        }

        if ($resourceShareTransfer->getUuid()) {
            $resourceShareQuery->filterByUuid($resourceShareTransfer->getUuid());
        }

        if ($resourceShareTransfer->getCustomerReference()) {
            $resourceShareQuery->filterByCustomerReference($resourceShareTransfer->getCustomerReference());
        }

        return $resourceShareQuery;
    }
}
