<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchantStore;

class MerchantStoreMapper
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[] $storeTransfers
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function mapStoreTransfersToStoreRelationTransfer(
        ArrayObject $storeTransfers,
        StoreRelationTransfer $storeRelationTransfer
    ): StoreRelationTransfer {
        $storeIds = array_map(function (StoreTransfer $storeTransfer) {
            return $storeTransfer->getIdStore();
        }, $storeTransfers->getArrayCopy());

        $storeRelationTransfer
            ->setStores($storeTransfers)
            ->setIdStores($storeIds);

        return $storeRelationTransfer;
    }

    /**
     * @param \Orm\Zed\Merchant\Persistence\SpyMerchantStore $merchantStoreEntity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function mapMerchantStoreEntityToStoreTransfer(
        SpyMerchantStore $merchantStoreEntity,
        StoreTransfer $storeTransfer
    ): StoreTransfer {
        return $storeTransfer->setIdStore($merchantStoreEntity->getFkStore())
            ->setName($merchantStoreEntity->getSpyStore()->getName());
    }
}
