<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductBundleStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\Synchronization\Persistence\Propel\Formatter\SynchronizationDataTransferObjectFormatter;

/**
 * @method \Spryker\Zed\ProductBundleStorage\Persistence\ProductBundleStoragePersistenceFactory getFactory()
 */
class ProductBundleStorageRepository extends AbstractRepository implements ProductBundleStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $concreteProductIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getPaginatedProductBundleStorageDataTransfers(FilterTransfer $filterTransfer, array $concreteProductIds): array
    {
        $productBundleStoragePropelQuery = $this->getFactory()
            ->getProductBundleStoragePropelQuery();

        if ($concreteProductIds) {
            $productBundleStoragePropelQuery->filterByFkProduct_In($concreteProductIds);
        }

        return $this->buildQueryFromCriteria($productBundleStoragePropelQuery, $filterTransfer)
            ->setFormatter(SynchronizationDataTransferObjectFormatter::class)
            ->find();
    }
}
