<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOptionStorage\Business\Writer;

use Spryker\Zed\MerchantProductOptionStorage\Dependency\Facade\MerchantProductOptionStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantProductOptionStorage\Dependency\Facade\MerchantProductOptionStorageToProductOptionStorageFacadeInterface;
use Spryker\Zed\MerchantProductOptionStorage\Persistence\MerchantProductOptionStorageRepositoryInterface;

class MerchantProductOptionStorageWriter implements MerchantProductOptionStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductOptionStorage\Dependency\Facade\MerchantProductOptionStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\MerchantProductOptionStorage\Persistence\MerchantProductOptionStorageRepositoryInterface
     */
    protected $merchantProductOptionStorageRepository;

    /**
     * @var \Spryker\Zed\MerchantProductOptionStorage\Dependency\Facade\MerchantProductOptionStorageToProductOptionStorageFacadeInterface
     */
    protected $productOptionStorageFacade;

    /**
     * @param \Spryker\Zed\MerchantProductOptionStorage\Dependency\Facade\MerchantProductOptionStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\MerchantProductOptionStorage\Persistence\MerchantProductOptionStorageRepositoryInterface $merchantProductOptionStorageRepository
     * @param \Spryker\Zed\MerchantProductOptionStorage\Dependency\Facade\MerchantProductOptionStorageToProductOptionStorageFacadeInterface $productOptionStorageFacade
     */
    public function __construct(
        MerchantProductOptionStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        MerchantProductOptionStorageRepositoryInterface $merchantProductOptionStorageRepository,
        MerchantProductOptionStorageToProductOptionStorageFacadeInterface $productOptionStorageFacade
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->merchantProductOptionStorageRepository = $merchantProductOptionStorageRepository;
        $this->productOptionStorageFacade = $productOptionStorageFacade;
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByMerchantProductOptionGroupEvents(array $eventTransfers): void
    {
        $merchantProductOptionGroupIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        if (!$merchantProductOptionGroupIds) {
            return;
        }

        $productAbstractIds = $this->merchantProductOptionStorageRepository
            ->getAbstractProductIdsByMerchantProductOptionGroupIds($merchantProductOptionGroupIds);

        $this->productOptionStorageFacade->publish($productAbstractIds);
    }
}
