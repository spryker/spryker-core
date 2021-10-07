<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationStorage\Persistence;

use Generated\Shared\Transfer\ProductConfigurationStorageTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

/**
 * @method \Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStoragePersistenceFactory getFactory()
 */
class ProductConfigurationStorageEntityManager extends AbstractEntityManager implements ProductConfigurationStorageEntityManagerInterface
{
    use TransactionTrait;

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationStorageTransfer $productConfigurationStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationStorageTransfer
     */
    public function saveProductConfigurationStorage(
        ProductConfigurationStorageTransfer $productConfigurationStorageTransfer
    ): ProductConfigurationStorageTransfer {
        $productConfigurationStorageEntity = $this->getFactory()
            ->createProductConfigurationStorageQuery()
            ->filterByFkProductConfiguration($productConfigurationStorageTransfer->getFkProductConfiguration())
            ->findOneOrCreate();

        $productConfigurationStorageEntity = $this->getFactory()->createProductConfigurationStorageMapper()
            ->mapProductConfigurationStorageTransferToProductConfigurationStorageEntity(
                $productConfigurationStorageEntity,
                $productConfigurationStorageTransfer
            );

        $productConfigurationStorageEntity->save();

        return $this->getFactory()->createProductConfigurationStorageMapper()
            ->mapProductConfigurationStorageEntityToProductConfigurationStorageTransfer(
                $productConfigurationStorageEntity,
                new ProductConfigurationStorageTransfer()
            );
    }

    /**
     * @param array<int> $productConfigurationIds
     *
     * @return void
     */
    public function deleteProductConfigurationStorageByProductConfigurationIds(array $productConfigurationIds): void
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductConfigurationStorage\Persistence\SpyProductConfigurationStorage> $productConfigurationStorageEntities */
        $productConfigurationStorageEntities = $this->getFactory()
            ->createProductConfigurationStorageQuery()
            ->filterByFkProductConfiguration_In($productConfigurationIds)
            ->find();

        $this->getTransactionHandler()->handleTransaction(function () use ($productConfigurationStorageEntities): void {
            $this->executeDeleteProductConfigurationStorageByProductConfigurationIds($productConfigurationStorageEntities);
        });
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductConfigurationStorage\Persistence\SpyProductConfigurationStorage> $productConfigurationStorageEntities
     *
     * @return void
     */
    protected function executeDeleteProductConfigurationStorageByProductConfigurationIds(
        ObjectCollection $productConfigurationStorageEntities
    ): void {
        foreach ($productConfigurationStorageEntities as $productConfigurationStorageEntity) {
            $productConfigurationStorageEntity->delete();
        }
    }
}
