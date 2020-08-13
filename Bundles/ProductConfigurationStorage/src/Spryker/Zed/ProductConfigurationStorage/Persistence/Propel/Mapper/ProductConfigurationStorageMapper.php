<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductConfigurationStorageTransfer;
use Orm\Zed\ProductConfigurationStorage\Persistence\SpyProductConfigurationStorage;

class ProductConfigurationStorageMapper
{
    /**
     * @param \Orm\Zed\ProductConfigurationStorage\Persistence\SpyProductConfigurationStorage $productConfigurationStorageEntity
     * @param \Generated\Shared\Transfer\ProductConfigurationStorageTransfer $productConfigurationStorageTransfer
     *
     * @return \Orm\Zed\ProductConfigurationStorage\Persistence\SpyProductConfigurationStorage
     */
    public function mapProductConfigurationStorageTransferToProductConfigurationStorageEntity(
        SpyProductConfigurationStorage $productConfigurationStorageEntity,
        ProductConfigurationStorageTransfer $productConfigurationStorageTransfer
    ): SpyProductConfigurationStorage {
        $productConfigurationStorageEntity->setData($productConfigurationStorageTransfer->toArray());
        $productConfigurationStorageEntity->setFkProduct($productConfigurationStorageTransfer->getFkProduct());

        return $productConfigurationStorageEntity;
    }

    /**
     * @param \Orm\Zed\ProductConfigurationStorage\Persistence\SpyProductConfigurationStorage $productConfigurationStorageEntity
     * @param \Generated\Shared\Transfer\ProductConfigurationStorageTransfer $productConfigurationStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationStorageTransfer
     */
    public function mapProductConfigurationStorageEntityToProductConfigurationStorageTransfer(
        SpyProductConfigurationStorage $productConfigurationStorageEntity,
        ProductConfigurationStorageTransfer $productConfigurationStorageTransfer
    ): ProductConfigurationStorageTransfer {
        return $productConfigurationStorageTransfer
            ->fromArray($productConfigurationStorageEntity->toArray(), true);
    }
}
