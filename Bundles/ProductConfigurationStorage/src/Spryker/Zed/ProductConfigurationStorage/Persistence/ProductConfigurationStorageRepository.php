<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\Synchronization\Persistence\Propel\Formatter\SynchronizationDataTransferObjectFormatter;

/**
 * @method \Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStoragePersistenceFactory getFactory()
 */
class ProductConfigurationStorageRepository extends AbstractRepository implements ProductConfigurationStorageRepositoryInterface
{
    /**
     * @param int $offset
     * @param int $limit
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function findProductConfigurationStorageDataTransferByIds(int $offset, int $limit, array $ids): array
    {
        $filterTransfer = $this->createFilterTransfer($offset, $limit);
        $query = $this->getFactory()->createProductConfigurationStorageQuery();

        if ($ids) {
            $query->filterByIdProductConfigurationStorage_In($ids);
        }

        return $this->buildQueryFromCriteria($query, $filterTransfer)
            ->setFormatter(SynchronizationDataTransferObjectFormatter::class)
            ->find();
    }

    /**
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationStorageTransfer[]
     */
    public function findProductConfigurationStorageTransfersByProductConfigurationIds(array $ids): array
    {
        $query = $this->getFactory()->createProductConfigurationStorageQuery()
            ->filterByFkProductConfiguration_In($ids);

        return $query->find()->toArray();
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
