<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationStorage\Persistence;

use Generated\Shared\Transfer\ProductConfigurationStorageTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStoragePersistenceFactory getFactory()
 */
class ProductConfigurationStorageEntityManager extends AbstractEntityManager implements ProductConfigurationStorageEntityManagerInterface
{
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
            ->mapProductConfigurationStorageEntity(
                $productConfigurationStorageEntity,
                $productConfigurationStorageTransfer
            );

        $productConfigurationStorageEntity->save();

        return (new ProductConfigurationStorageTransfer())
            ->fromArray($productConfigurationStorageEntity->toArray(), true);
    }

    /**
     * @param int[] $productConfigurationIds
     *
     * @return void
     */
    public function deleteProductConfigurationStorageByFkProductConfiguration(array $productConfigurationIds): void
    {
        $productConfigurationStorageEntities = $this->getFactory()
            ->createProductConfigurationStorageQuery()
            ->filterByFkProductConfiguration_In($productConfigurationIds)
            ->find();

        foreach ($productConfigurationStorageEntities as $productConfigurationStorageEntity) {
            $productConfigurationStorageEntity->delete();
        }
    }
}
