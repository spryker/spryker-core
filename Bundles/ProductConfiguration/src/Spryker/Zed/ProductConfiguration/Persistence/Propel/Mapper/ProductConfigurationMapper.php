<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductConfigurationCollectionTransfer;
use Generated\Shared\Transfer\ProductConfigurationTransfer;
use Orm\Zed\ProductConfiguration\Persistence\SpyProductConfiguration;
use Propel\Runtime\Collection\ObjectCollection;

class ProductConfigurationMapper
{
    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationCollectionTransfer $productConfigurationCollectionTransfer
     * @param \Propel\Runtime\Collection\ObjectCollection $productConfigurationEntitiesCollection
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationCollectionTransfer
     */
    public function mapProductConfigurationEntityCollectionToProductConfigurationTransferCollection(
        ProductConfigurationCollectionTransfer $productConfigurationCollectionTransfer,
        ObjectCollection $productConfigurationEntitiesCollection
    ): ProductConfigurationCollectionTransfer {
        foreach ($productConfigurationEntitiesCollection as $productConfigurationEntity) {
            $productConfigurationCollectionTransfer->addProductConfiguration(
                $this->mapProductConfigurationEntityToProductConfigurationTransfer(
                    new ProductConfigurationTransfer(),
                    $productConfigurationEntity
                )
            );
        }

        return $productConfigurationCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationTransfer $productConfigurationTransfer
     * @param \Orm\Zed\ProductConfiguration\Persistence\SpyProductConfiguration $productConfigurationEntity
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationTransfer
     */
    public function mapProductConfigurationEntityToProductConfigurationTransfer(
        ProductConfigurationTransfer $productConfigurationTransfer,
        SpyProductConfiguration $productConfigurationEntity
    ): ProductConfigurationTransfer {
            $productConfigurationTransfer->setSku(
                $productConfigurationEntity->getSpyProduct()->getSku()
            );

        return $productConfigurationTransfer->fromArray($productConfigurationEntity->toArray(), true);
    }
}
