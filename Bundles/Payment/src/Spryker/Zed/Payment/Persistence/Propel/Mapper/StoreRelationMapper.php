<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Store\Persistence\SpyStore;
use Propel\Runtime\Collection\ObjectCollection;

class StoreRelationMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Payment\Persistence\SpyPaymentMethodStore[] $paymentMethodStoreEntities
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function mapPaymentMethodStoreEntitiesToStoreRelationTransfer(
        ObjectCollection $paymentMethodStoreEntities,
        StoreRelationTransfer $storeRelationTransfer
    ): StoreRelationTransfer {
        foreach ($paymentMethodStoreEntities as $paymentMethodStoreEntity) {
            $storeRelationTransfer->addStores($this->mapStoreEntityToStoreTransfer($paymentMethodStoreEntity->getSpyStore(), new StoreTransfer()));
            $storeRelationTransfer->addIdStores($paymentMethodStoreEntity->getFkStore());
        }

        return $storeRelationTransfer;
    }

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStore $storeEntity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function mapStoreEntityToStoreTransfer(
        SpyStore $storeEntity,
        StoreTransfer $storeTransfer
    ): StoreTransfer {
        return $storeTransfer->fromArray($storeEntity->toArray(), true);
    }
}
