<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\Store;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToStoreFacadeInterface;
use Spryker\Zed\Store\Business\Model\Exception\StoreNotFoundException;

class StoreFinder implements StoreFinderInterface
{
    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var array
     */
    protected $storeCache = [];

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToStoreFacadeInterface $storeFacade
     */
    public function __construct(PriceProductScheduleToStoreFacadeInterface $storeFacade)
    {
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer|null
     */
    public function findStoreByName(string $storeName): ?StoreTransfer
    {
        if (isset($this->storeCache[$storeName])) {
            return $this->storeCache[$storeName];
        }

        try {
            $storeTransfer = $this->storeFacade->getStoreByName($storeName);

            $this->storeCache[$storeName] = $storeTransfer;

            return $this->storeCache[$storeName];
        } catch (StoreNotFoundException $e) {
            return null;
        }
    }
}
