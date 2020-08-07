<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductConfigurationCollectionTransfer;
use Generated\Shared\Transfer\ProductConfigurationTransfer;
use Propel\Runtime\Collection\ObjectCollection;

class ProductConfigurationMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $productConfigurationEntitiesCollection
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationCollectionTransfer
     */
    public function mapProductConfigurationEntityCollectionToProductConfigurationTransferCollection(
        ObjectCollection $productConfigurationEntitiesCollection
    ): ProductConfigurationCollectionTransfer {
        $productConfigurationCollectionTransfer = new ProductConfigurationCollectionTransfer();
        foreach ($productConfigurationEntitiesCollection as $productConfigurationEntity) {
            $productConfigurationCollectionTransfer->addProductConfiguration(
                (new ProductConfigurationTransfer())->fromArray($productConfigurationEntity->toArray(), true)
            );
        }

        return $productConfigurationCollectionTransfer;
    }
}
