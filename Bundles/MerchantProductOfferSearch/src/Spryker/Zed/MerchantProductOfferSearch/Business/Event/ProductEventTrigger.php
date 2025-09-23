<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Business\Event;

use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Shared\MerchantProductOfferSearch\MerchantProductOfferSearchConfig;
use Spryker\Zed\MerchantProductOfferSearch\Dependency\Facade\MerchantProductOfferSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantProductOfferSearch\Dependency\Facade\MerchantProductOfferSearchToEventFacadeInterface;
use Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepositoryInterface;

class ProductEventTrigger implements ProductEventTriggerInterface
{
    /**
     * @var int
     *
     * @phpstan-var positive-int
     */
    protected const CHUNK_SIZE = 1000;

    /**
     * @param \Spryker\Zed\MerchantProductOfferSearch\Dependency\Facade\MerchantProductOfferSearchToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepositoryInterface $repository
     * @param \Spryker\Zed\MerchantProductOfferSearch\Dependency\Facade\MerchantProductOfferSearchToEventFacadeInterface $eventFacade
     */
    public function __construct(
        protected MerchantProductOfferSearchToEventBehaviorFacadeInterface $eventBehaviorFacade,
        protected MerchantProductOfferSearchRepositoryInterface $repository,
        protected MerchantProductOfferSearchToEventFacadeInterface $eventFacade
    ) {
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function triggerMerchantProducts(array $eventEntityTransfers): void
    {
        $merchantIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);
        if (!$merchantIds) {
            return;
        }

        $productAbstractIds = $this->repository->getProductAbstractIdsByMerchantIds($merchantIds);
        $this->triggerProductAbstractPublishEvent($productAbstractIds);
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    protected function triggerProductAbstractPublishEvent(array $productAbstractIds): void
    {
        foreach (array_chunk($productAbstractIds, static::CHUNK_SIZE) as $productAbstractIdsChunk) {
            $eventEntityTransfers = [];

            foreach ($productAbstractIdsChunk as $idProductAbstract) {
                $eventEntityTransfers[] = (new EventEntityTransfer())
                    ->setId($idProductAbstract);
            }

            $this->eventFacade->triggerBulk(
                MerchantProductOfferSearchConfig::PRODUCT_ABSTRACT_SEARCH_PUBLISH,
                $eventEntityTransfers,
            );
        }
    }
}
