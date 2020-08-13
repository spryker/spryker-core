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
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productConfigurationStorageIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getFilteredProductConfigurationStorageDataTransfers(
        FilterTransfer $filterTransfer,
        array $productConfigurationStorageIds
    ): array {
        $query = $this->getFactory()->createProductConfigurationStorageQuery();

        if ($productConfigurationStorageIds) {
            $query->filterByIdProductConfigurationStorage_In($productConfigurationStorageIds);
        }

        return $this->buildQueryFromCriteria($query, $filterTransfer)
            ->setFormatter(SynchronizationDataTransferObjectFormatter::class)
            ->find();
    }
}
