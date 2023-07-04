<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\Synchronization\Persistence\Propel\Formatter\SynchronizationDataTransferObjectFormatter;

/**
 * @method \Spryker\Zed\ProductOfferServicePointStorage\Persistence\ProductOfferServicePointStoragePersistenceFactory getFactory()
 */
class ProductOfferServicePointStorageRepository extends AbstractRepository implements ProductOfferServicePointStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param list<string> $productOfferReferences
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getProductOfferServiceStorageSynchronizationDataTransfers(FilterTransfer $filterTransfer, array $productOfferReferences = []): array
    {
        $productOfferServiceStorageQuery = $this->getFactory()->getProductOfferServiceStorageQuery();

        if ($productOfferReferences) {
            $productOfferServiceStorageQuery->filterByProductOfferReference_In($productOfferReferences);
        }

        /** @var array<\Generated\Shared\Transfer\SynchronizationDataTransfer> */
        return $this->buildQueryFromCriteria($productOfferServiceStorageQuery, $filterTransfer)
            ->setFormatter(SynchronizationDataTransferObjectFormatter::class)
            ->find();
    }
}
