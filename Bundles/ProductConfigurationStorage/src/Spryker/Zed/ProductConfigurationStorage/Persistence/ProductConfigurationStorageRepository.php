<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationStorage\Persistence;

use Generated\Shared\Transfer\ProductConfigurationFilterTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\Synchronization\Persistence\Propel\Formatter\SynchronizationDataTransferObjectFormatter;

/**
 * @method \Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStoragePersistenceFactory getFactory()
 */
class ProductConfigurationStorageRepository extends AbstractRepository implements ProductConfigurationStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationFilterTransfer $productConfigurationFilterTransfer
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getProductConfigurationStorageDataTransfersByCriteria(
        ProductConfigurationFilterTransfer $productConfigurationFilterTransfer
    ): array {
        $filterTransfer = $productConfigurationFilterTransfer->getFilter();
        $query = $this->getFactory()->createProductConfigurationStorageQuery();

        if ($productConfigurationFilterTransfer->getProductConfigurationStorageIds()) {
            $query->filterByIdProductConfigurationStorage_In(
                $productConfigurationFilterTransfer->getProductConfigurationStorageIds()
            );
        }

        return $this->buildQueryFromCriteria($query, $filterTransfer)
            ->setFormatter(SynchronizationDataTransferObjectFormatter::class)
            ->find();
    }
}
