<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductStorage\Business\Deleter;

use Orm\Zed\MerchantProduct\Persistence\Map\SpyMerchantProductAbstractTableMap;
use Spryker\Zed\MerchantProductStorage\Dependency\Facade\MerchantProductStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantProductStorage\Dependency\Facade\MerchantProductStorageToProductStorageFacadeInterface;

class MerchantProductStorageDeleter implements MerchantProductStorageDeleterInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductStorage\Dependency\Facade\MerchantProductStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\MerchantProductStorage\Dependency\Facade\MerchantProductStorageToProductStorageFacadeInterface
     */
    protected $productStorageFacade;

    /**
     * @param \Spryker\Zed\MerchantProductStorage\Dependency\Facade\MerchantProductStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\MerchantProductStorage\Dependency\Facade\MerchantProductStorageToProductStorageFacadeInterface $productStorageFacade
     */
    public function __construct(
        MerchantProductStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        MerchantProductStorageToProductStorageFacadeInterface $productStorageFacade
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productStorageFacade = $productStorageFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function deleteByIdProductAbstractEvents(array $eventTransfers): void
    {
        $idProductAbstracts = $this->eventBehaviorFacade->getEventTransfersAdditionalValues(
            $eventTransfers,
            SpyMerchantProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT
        );

        if (!$idProductAbstracts) {
            return;
        }

        $this->productStorageFacade->publishAbstractProducts($idProductAbstracts);
    }
}
