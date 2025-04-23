<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Persistence\Mapper;

use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;
use Generated\Shared\Transfer\ProductAvailabilityDataTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Orm\Zed\Availability\Persistence\SpyAvailability;
use Orm\Zed\Product\Persistence\SpyProduct;
use Propel\Runtime\Collection\Collection;

interface AvailabilityMapperInterface
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
    ): ProductConcreteAvailabilityTransfer;

    /**
     * @param array $availabilityAbstractEntityArray
     * @param \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer
     */
    public function mapAvailabilityEntityToProductAbstractAvailabilityTransfer(
        array $availabilityAbstractEntityArray,
        ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer
    ): ProductAbstractAvailabilityTransfer;

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Availability\Persistence\SpyAvailability> $availabilityEntities
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityCollectionTransfer $productConcreteAvailabilityCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityCollectionTransfer
     */
    public function mapAvailabilityEntitiesToProductConcreteAvailabilityCollectionTransfer(
        Collection $availabilityEntities,
        ProductConcreteAvailabilityCollectionTransfer $productConcreteAvailabilityCollectionTransfer
    ): ProductConcreteAvailabilityCollectionTransfer;

    /**
     * @param \Propel\Runtime\Collection\Collection $availabilityEntities
     * @param \Orm\Zed\Product\Persistence\SpyProduct|null $productConcreteEntity
     * @param \Generated\Shared\Transfer\ProductAvailabilityDataTransfer $productAvailabilityDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAvailabilityDataTransfer
     */
    public function mapAvailabilityEntitiesAndProductConcreteEntityToProductAvailabilityDataTransfer(
        Collection $availabilityEntities,
        ?SpyProduct $productConcreteEntity,
        ProductAvailabilityDataTransfer $productAvailabilityDataTransfer
    ): ProductAvailabilityDataTransfer;
}
