<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Propel\Runtime\Collection\ObjectCollection;

interface MerchantStoreMapperInterface
{
    /**
     * @param \Orm\Zed\Merchant\Persistence\SpyMerchantStore[]|\Propel\Runtime\Collection\ObjectCollection $merchantStoreEntities
     * @param \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[] $storesTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[]
     */
    public function mapMerchantStoreEntitiesToStoreTransferCollection(ObjectCollection $merchantStoreEntities, ArrayObject $storesTransfers): ArrayObject;

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[] $storeTransfers
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function mapStoreTransfersToStoreRelationTransfer(
        ArrayObject $storeTransfers,
        StoreRelationTransfer $storeRelationTransfer
    ): StoreRelationTransfer;
}
