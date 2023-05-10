<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\Synchronization\Persistence\Propel\Formatter\SynchronizationDataTransferObjectFormatter;

/**
 * @method \Spryker\Zed\ServicePointStorage\Persistence\ServicePointStoragePersistenceFactory getFactory()
 */
class ServicePointStorageRepository extends AbstractRepository implements ServicePointStorageRepositoryInterface
{
    /**
     * @param int $offset
     * @param int $limit
     * @param list<int> $servicePointIds
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getServicePointStorageSynchronizationDataTransfers(int $offset, int $limit, array $servicePointIds = []): array
    {
        $servicePointStorageQuery = $this->getFactory()->getServicePointStorageQuery();

        if ($servicePointIds) {
            $servicePointStorageQuery->filterByFkServicePoint_In($servicePointIds);
        }

        /** @var array<\Generated\Shared\Transfer\SynchronizationDataTransfer> */
        return $this->buildQueryFromCriteria($servicePointStorageQuery, $this->createFilterTransfer($offset, $limit))
            ->setFormatter(SynchronizationDataTransferObjectFormatter::class)
            ->find();
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
            ->setOffset($offset)
            ->setLimit($limit);
    }
}
