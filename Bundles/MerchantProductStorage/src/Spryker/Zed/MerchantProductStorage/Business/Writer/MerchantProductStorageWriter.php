<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductStorage\Business\Writer;

use Spryker\Zed\MerchantProductStorage\Dependency\Facade\MerchantProductStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantProductStorage\Persistence\MerchantProductStorageEntityManagerInterface;
use Spryker\Zed\MerchantProductStorage\Persistence\MerchantProductStorageRepositoryInterface;

class MerchantProductStorageWriter implements MerchantProductStorageWriterInterface
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
     * @var \Spryker\Zed\MerchantProductStorage\Persistence\MerchantProductStorageRepositoryInterface
     */
    protected $merchantProductStorageRepository;

    /**
     * @param \Spryker\Zed\MerchantProductStorage\Dependency\Facade\MerchantProductStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\MerchantProductStorage\Persistence\MerchantProductStorageEntityManagerInterface $merchantProductStorageEntityManager
     * @param \Spryker\Zed\MerchantProductStorage\Persistence\MerchantProductStorageRepositoryInterface $merchantProductStorageRepository
     */
    public function __construct(
        MerchantProductStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        MerchantProductStorageEntityManagerInterface $merchantProductStorageEntityManager,
        MerchantProductStorageRepositoryInterface $merchantProductStorageRepository
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->merchantProductStorageEntityManager = $merchantProductStorageEntityManager;
        $this->merchantProductStorageRepository = $merchantProductStorageRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByIdProductAbstractEvents(array $eventTransfers): void
    {
        $merchantProductAbstractIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        if (!$merchantProductAbstractIds) {
            return;
        }

        $this->writeByIdMerchantProductAbstracts($merchantProductAbstractIds);
    }

    /**
     * @param int[] $merchantProductAbstractIds
     *
     * @return void
     */
    protected function writeByIdMerchantProductAbstracts(array $merchantProductAbstractIds): void
    {
        $merchantProductsCollectionTransfer = $this->merchantProductStorageRepository
            ->getMerchantProducts($merchantProductAbstractIds);

        foreach ($merchantProductsCollectionTransfer->getMerchantProducts() as $merchantProductTransfer) {
            $this->merchantProductStorageEntityManager->saveMerchantProductStorage($merchantProductTransfer);
        }
    }
}
