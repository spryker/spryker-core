<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductBundleStorage\Persistence;

use Generated\Shared\Transfer\ProductBundleStorageTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductBundleStorage\Persistence\ProductBundleStoragePersistenceFactory getFactory()
 */
class ProductBundleStorageEntityManager extends AbstractEntityManager implements ProductBundleStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductBundleStorageTransfer $productBundleStorageTransfer
     *
     * @return void
     */
    public function saveProductBundleStorage(ProductBundleStorageTransfer $productBundleStorageTransfer): void
    {
        $productBundleStorageEntity = $this->getFactory()
            ->getProductBundleStoragePropelQuery()
            ->filterByFkProduct($productBundleStorageTransfer->getIdProductConcreteBundle())
            ->findOneOrCreate();

        $productBundleStorageEntity
            ->setData($productBundleStorageTransfer->toArray())
            ->save();
    }
}
