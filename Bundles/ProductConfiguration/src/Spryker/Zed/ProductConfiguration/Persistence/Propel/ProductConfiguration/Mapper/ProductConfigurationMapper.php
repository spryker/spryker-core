<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Persistence\Propel\ProductConfiguration\Mapper;

use Generated\Shared\Transfer\ProductConfigurationCollectionTransfer;
use Generated\Shared\Transfer\ProductConfigurationTransfer;
use Orm\Zed\ProductConfiguration\Persistence\SpyProductConfiguration;
use Propel\Runtime\Collection\ObjectCollection;

class ProductConfigurationMapper
{
    /**
     * @param \Orm\Zed\ProductConfiguration\Persistence\SpyProductConfiguration $productConfigurationEntity
     * @param \Generated\Shared\Transfer\ProductConfigurationTransfer $productConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationTransfer
     */
    public function mapProductConfigurationEntityToProductConfigurationTransfer(
        SpyProductConfiguration $productConfigurationEntity,
        ProductConfigurationTransfer $productConfigurationTransfer
    ): ProductConfigurationTransfer {
        $productConfigurationTransfer->setSku(
            $productConfigurationEntity->getSpyProduct()->getSku(),
        );

        return $productConfigurationTransfer->fromArray($productConfigurationEntity->toArray(), true);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductConfiguration\Persistence\SpyProductConfiguration> $productConfigurationEntityCollection
     * @param \Generated\Shared\Transfer\ProductConfigurationCollectionTransfer $productConfigurationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationCollectionTransfer
     */
    public function mapProductConfigurationEntityCollectionToProductConfigurationCollectionTransfer(
        ObjectCollection $productConfigurationEntityCollection,
        ProductConfigurationCollectionTransfer $productConfigurationCollectionTransfer
    ): ProductConfigurationCollectionTransfer {
        foreach ($productConfigurationEntityCollection as $productConfigurationEntity) {
            $productConfigurationTransfer = $this->mapProductConfigurationEntityToProductConfigurationTransfer(
                $productConfigurationEntity,
                new ProductConfigurationTransfer(),
            );

            $productConfigurationCollectionTransfer->addProductConfiguration($productConfigurationTransfer);
        }

        return $productConfigurationCollectionTransfer;
    }
}
