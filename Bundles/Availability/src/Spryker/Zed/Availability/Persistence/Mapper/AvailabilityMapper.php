<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Persistence\Mapper;

use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Orm\Zed\Availability\Persistence\SpyAvailability;

class AvailabilityMapper implements AvailabilityMapperInterface
{
    /**
     * @param \Orm\Zed\Availability\Persistence\SpyAvailability $availabilityEntity
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer
     */
    public function mapAvailabilityEntityToProductConcreteAvailabilityTransfer(
        SpyAvailability $availabilityEntity,
        ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
    ): ProductConcreteAvailabilityTransfer {
        return $productConcreteAvailabilityTransfer
            ->setSku($availabilityEntity->getSku())
            ->setAvailability($availabilityEntity->getQuantity())
            ->setIsNeverOutOfStock($availabilityEntity->getIsNeverOutOfStock());
    }

    /**
     * @param array $availabilityAbstractEntityArray
     * @param \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer
     */
    public function mapAvailabilityEntityToProductAbstractAvailabilityTransfer(
        array $availabilityAbstractEntityArray,
        ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer
    ): ProductAbstractAvailabilityTransfer {
        return $productAbstractAvailabilityTransfer
            ->fromArray($availabilityAbstractEntityArray, true)
            ->setIsNeverOutOfStock(
                stripos(
                    $availabilityAbstractEntityArray[ProductAbstractAvailabilityTransfer::IS_NEVER_OUT_OF_STOCK],
                    'true'
                ) !== false
            );
    }
}
