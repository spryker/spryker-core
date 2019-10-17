<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Store\Persistence\SpyStore;

interface StoreMapperInterface
{
    /**
     * @param \Orm\Zed\Store\Persistence\SpyStore[] $storeEntities
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function mapStoreEntitiesToStoreTransfers(array $storeEntities): array;

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStore $storeEntity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function mapStoreEntityToStoreTransfer(SpyStore $storeEntity, StoreTransfer $storeTransfer): StoreTransfer;
}
