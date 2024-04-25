<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\StoreCollectionTransfer;
use Generated\Shared\Transfer\StoreConditionsTransfer;
use Generated\Shared\Transfer\StoreCriteriaTransfer;
use Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToStoreFacadeInterface;

class StoreReader implements StoreReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToStoreFacadeInterface
     */
    protected MerchantCommissionToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToStoreFacadeInterface $storeFacade
     */
    public function __construct(MerchantCommissionToStoreFacadeInterface $storeFacade)
    {
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param list<int> $storeIds
     *
     * @return \Generated\Shared\Transfer\StoreCollectionTransfer
     */
    public function getStoreCollectionByStoreIds(array $storeIds): StoreCollectionTransfer
    {
        $storeConditionsTransfer = (new StoreConditionsTransfer())->setStoreIds($storeIds);
        $storeCriteriaTransfer = (new StoreCriteriaTransfer())->setStoreConditions($storeConditionsTransfer);

        return $this->storeFacade->getStoreCollection($storeCriteriaTransfer);
    }

    /**
     * @param list<string> $storeNames
     *
     * @return \Generated\Shared\Transfer\StoreCollectionTransfer
     */
    public function getStoreCollectionByStoreNames(array $storeNames): StoreCollectionTransfer
    {
        $storeTransfers = $this->storeFacade->getStoreTransfersByStoreNames($storeNames);

        return (new StoreCollectionTransfer())->setStores(new ArrayObject($storeTransfers));
    }
}
