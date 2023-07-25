<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Business\Reader;

use Generated\Shared\Transfer\StoreCriteriaTransfer;
use Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade\ProductOfferAvailabilityStorageToStoreFacadeInterface;

class StoreReader implements StoreReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade\ProductOfferAvailabilityStorageToStoreFacadeInterface
     */
    protected ProductOfferAvailabilityStorageToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade\ProductOfferAvailabilityStorageToStoreFacadeInterface $storeFacade
     */
    public function __construct(ProductOfferAvailabilityStorageToStoreFacadeInterface $storeFacade)
    {
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreCriteriaTransfer $storeCriteriaTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\StoreTransfer>
     */
    public function getStoreTransfersIndexedByIdStore(StoreCriteriaTransfer $storeCriteriaTransfer): array
    {
        $storeCollectionTransfer = $this->storeFacade->getStoreCollection($storeCriteriaTransfer);

        $storeTransfersIndexedByIdStore = [];
        foreach ($storeCollectionTransfer->getStores() as $storeTransfer) {
            $storeTransfersIndexedByIdStore[$storeTransfer->getIdStoreOrFail()] = $storeTransfer;
        }

        return $storeTransfersIndexedByIdStore;
    }
}
