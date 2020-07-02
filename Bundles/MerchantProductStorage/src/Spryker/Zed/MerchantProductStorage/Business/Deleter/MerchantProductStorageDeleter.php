<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductStorage\Business\Deleter;

use Orm\Zed\MerchantProduct\Persistence\Map\SpyMerchantProductAbstractTableMap;
use Spryker\Zed\MerchantProductStorage\Dependency\Facade\MerchantProductStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantProductStorage\Persistence\MerchantProductStorageEntityManagerInterface;

class MerchantProductStorageDeleter implements MerchantProductStorageDeleterInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductStorage\Dependency\Facade\MerchantProductStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\MerchantProductStorage\Persistence\MerchantProductStorageEntityManagerInterface
     */
    protected $merchantProductStorageEntityManager;

    /**
     * @param \Spryker\Zed\MerchantProductStorage\Dependency\Facade\MerchantProductStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\MerchantProductStorage\Persistence\MerchantProductStorageEntityManagerInterface $merchantProductStorageEntityManager
     */
    public function __construct(
        MerchantProductStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        MerchantProductStorageEntityManagerInterface $merchantProductStorageEntityManager
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->merchantProductStorageEntityManager = $merchantProductStorageEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function deleteCollectionByIdProductAbstractEvents(array $eventTransfers): void
    {
        $idProductAbstracts = $this->eventBehaviorFacade->getEventTransfersAdditionalValues(
            $eventTransfers,
            SpyMerchantProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT
        );

        if (!$idProductAbstracts) {
            return;
        }

        $this->deleteByIdProductAbstracts($idProductAbstracts);
    }

    /**
     * @param int[] $idProductAbstracts
     *
     * @return void
     */
    protected function deleteByIdProductAbstracts(array $idProductAbstracts): void
    {
        $this->merchantProductStorageEntityManager->deleteMerchantProductStorageEntitiesByIdProductAbstracts($idProductAbstracts);
    }
}
