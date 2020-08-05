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
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getPaginatedProductBundleStorageDataTransfers(FilterTransfer $filterTransfer, array $productConcreteIds): array
    {
        $productBundleStoragePropelQuery = $this->getFactory()
            ->getProductBundleStoragePropelQuery();

        if ($productConcreteIds) {
            $productBundleStoragePropelQuery->filterByFkProduct_In($productConcreteIds);
        }

        return $this->buildQueryFromCriteria($productBundleStoragePropelQuery, $filterTransfer)
            ->setFormatter(SynchronizationDataTransferObjectFormatter::class)
            ->find();
    }
}
