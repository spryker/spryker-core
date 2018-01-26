<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Model;

use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Store\Persistence\SpyStore;
use Spryker\Shared\Store\Reader\KernelStoreReaderInterface;

class StoreMapper implements StoreMapperInterface
{
    /**
     * @var \Spryker\Shared\Store\Reader\KernelStoreReaderInterface
     */
    protected $kernelStoreReader;

    /**
     * @param \Spryker\Shared\Store\Reader\KernelStoreReaderInterface $kernelStoreReader
     */
    public function __construct(KernelStoreReaderInterface $kernelStoreReader)
    {
        $this->kernelStoreReader = $kernelStoreReader;
    }

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStore $storeEntity
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function mapEntityToTransfer(SpyStore $storeEntity)
    {
        $storeName = $storeEntity->getName();

        $storeTransfer = $this->kernelStoreReader->getStoreByName($storeName);

        return $storeTransfer->fromArray($storeEntity->toArray(), true);
    }

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStore $storeEntity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Orm\Zed\Store\Persistence\SpyStore
     */
    public function mapTransferToEntity(SpyStore $storeEntity, StoreTransfer $storeTransfer)
    {
        $storeEntity->fromArray($storeTransfer->toArray());

        return $storeEntity;
    }
}
