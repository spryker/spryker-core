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

class ProductConfigurationMapper implements ProductConfigurationMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $productConfigurationEntitiesCollection
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationCollectionTransfer
     */
    public function mapEntityCollectionToTransferCollection(
        ObjectCollection $productConfigurationEntitiesCollection
    ): ProductConfigurationCollectionTransfer {
        $productConfigurationCollectionTransfer = new ProductConfigurationCollectionTransfer();
        foreach ($productConfigurationEntitiesCollection as $productConfigurationEntity) {
            $productConfigurationCollectionTransfer->addProductConfiguration(
                $this->mapProductConfigurationEntityToTransfer(
                    $productConfigurationEntity
                )
            );
        }

        return $productConfigurationCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\ProductConfiguration\Persistence\SpyProductConfiguration $productConfigurationEntity
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationTransfer
     */
    protected function mapProductConfigurationEntityToTransfer(SpyProductConfiguration $productConfigurationEntity)
    {
        return (new ProductConfigurationTransfer())->fromArray($productConfigurationEntity->toArray(), true);
    }
}
