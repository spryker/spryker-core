<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Store\Persistence\SpyStore;
use Spryker\Shared\Store\Reader\StoreReaderInterface;

class StoreMapper
{
    /**
     * @var \Spryker\Shared\Store\Reader\StoreReaderInterface
     */
    protected $storeReader;

    /**
     * @param \Spryker\Shared\Store\Reader\StoreReaderInterface $storeReader
     */
    public function __construct(StoreReaderInterface $storeReader)
    {
        $this->storeReader = $storeReader;
    }

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStore $storeEntity
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function mapStoreEntityToStoreTransfer(SpyStore $storeEntity): StoreTransfer
    {
        $storeName = $storeEntity->getName();

        $storeTransfer = $this->storeReader->getStoreByName($storeName);

        return $storeTransfer->fromArray($storeEntity->toArray(), true);
    }
}
