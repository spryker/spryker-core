<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Writer;

use Spryker\Zed\ProductPageSearch\Business\Publisher\ProductConcretePageSearchPublisherInterface;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface;

class ProductConcretePageSearchByProductEventsWriter implements ProductConcretePageSearchByProductEventsWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductPageSearch\Business\Publisher\ProductConcretePageSearchPublisherInterface
     */
    protected ProductConcretePageSearchPublisherInterface $productConcretePageSearchPublisher;

    /**
     * @var \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToEventBehaviorFacadeInterface
     */
    protected ProductPageSearchToEventBehaviorFacadeInterface $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface
     */
    protected ProductPageSearchRepositoryInterface $productPageSearchRepository;

    /**
     * @param \Spryker\Zed\ProductPageSearch\Business\Publisher\ProductConcretePageSearchPublisherInterface $productConcretePageSearchPublisher
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface $productPageSearchRepository
     */
    public function __construct(
        ProductConcretePageSearchPublisherInterface $productConcretePageSearchPublisher,
        ProductPageSearchToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ProductPageSearchRepositoryInterface $productPageSearchRepository
    ) {
        $this->productConcretePageSearchPublisher = $productConcretePageSearchPublisher;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productPageSearchRepository = $productPageSearchRepository;
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductConcretePageSearchCollectionByProductEvents(array $eventEntityTransfers): void
    {
        $productSearchIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);
        $productIds = $this->productPageSearchRepository->getProductConcreteIdsByProductSearchIds($productSearchIds);

        $this->productConcretePageSearchPublisher->publish($productIds);
    }
}
