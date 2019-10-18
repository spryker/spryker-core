<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Store\Persistence\SpyStore;
use Propel\Runtime\Collection\ObjectCollection;

interface StoreRelationMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Shipment\Persistence\SpyShipmentMethodStore[] $shipmentMethodStoreEntities
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function mapShipmentMethodStoreEntitiesToStoreRelationTransfer(ObjectCollection $shipmentMethodStoreEntities, StoreRelationTransfer $storeRelationTransfer): StoreRelationTransfer;

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStore $storeEntity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function mapStoreEntityToStoreTransfer(SpyStore $storeEntity, StoreTransfer $storeTransfer): StoreTransfer;
}
