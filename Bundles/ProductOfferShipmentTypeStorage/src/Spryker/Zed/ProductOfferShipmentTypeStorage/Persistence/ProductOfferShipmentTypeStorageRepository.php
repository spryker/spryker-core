<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\Synchronization\Persistence\Propel\Formatter\SynchronizationDataTransferObjectFormatter;

/**
 * @method \Spryker\Zed\ProductOfferShipmentTypeStorage\Persistence\ProductOfferShipmentTypeStoragePersistenceFactory getFactory()
 */
class ProductOfferShipmentTypeStorageRepository extends AbstractRepository implements ProductOfferShipmentTypeStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param list<string> $productOfferReferences
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getProductOfferShipmentTypeStorageSynchronizationDataTransfers(
        FilterTransfer $filterTransfer,
        array $productOfferReferences = []
    ): array {
        $productOfferShipmentTypeStorageQuery = $this->getFactory()->createProductOfferShipmentTypeStorageQuery();

        if ($productOfferReferences) {
            $productOfferShipmentTypeStorageQuery->filterByProductOfferReference_In($productOfferReferences);
        }

        return $this->buildQueryFromCriteria($productOfferShipmentTypeStorageQuery, $filterTransfer)
            ->setFormatter(SynchronizationDataTransferObjectFormatter::class)
            ->find();
    }
}
