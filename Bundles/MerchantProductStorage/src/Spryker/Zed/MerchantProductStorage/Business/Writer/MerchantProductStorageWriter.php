<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductStorage\Business\Writer;

use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Spryker\Zed\MerchantProductStorage\Dependency\Facade\MerchantProductStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantProductStorage\Dependency\Facade\MerchantProductStorageToMerchantProductFacadeInterface;
use Spryker\Zed\MerchantProductStorage\Dependency\Facade\MerchantProductStorageToProductStorageFacadeInterface;

class MerchantProductStorageWriter implements MerchantProductStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductStorage\Dependency\Facade\MerchantProductStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\MerchantProductStorage\Dependency\Facade\MerchantProductStorageToMerchantProductFacadeInterface
     */
    protected $merchantProductFacade;

    /**
     * @var \Spryker\Zed\MerchantProductStorage\Dependency\Facade\MerchantProductStorageToProductStorageFacadeInterface
     */
    protected $productStorageFacade;

    /**
     * @param \Spryker\Zed\MerchantProductStorage\Dependency\Facade\MerchantProductStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\MerchantProductStorage\Dependency\Facade\MerchantProductStorageToMerchantProductFacadeInterface $merchantProductFacade
     * @param \Spryker\Zed\MerchantProductStorage\Dependency\Facade\MerchantProductStorageToProductStorageFacadeInterface $productStorageFacade
     */
    public function __construct(
        MerchantProductStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        MerchantProductStorageToMerchantProductFacadeInterface $merchantProductFacade,
        MerchantProductStorageToProductStorageFacadeInterface $productStorageFacade
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->merchantProductFacade = $merchantProductFacade;
        $this->productStorageFacade = $productStorageFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeByIdProductAbstractMerchantEvents(array $eventTransfers): void
    {
        $productAbstractMerchantIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        if (!$productAbstractMerchantIds) {
            return;
        }

        $merchantProductCriteriaTransfer = (new MerchantProductCriteriaTransfer())
            ->setIdProductAbstractMerchants($productAbstractMerchantIds);

        $merchantProductTransfers = $this->merchantProductFacade->get($merchantProductCriteriaTransfer);

        if (!$merchantProductTransfers) {
            return;
        }

        $productAbstractIds = [];

        foreach ($merchantProductTransfers as $merchantProductTransfer) {
            $productAbstractIds[] = $merchantProductTransfer->getIdProductAbstract();
        }

        if (!$productAbstractIds) {
            return;
        }

        $this->productStorageFacade->publishAbstractProducts($productAbstractIds);
    }
}
