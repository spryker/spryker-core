<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductSearch\Business\Writer;

use Spryker\Zed\MerchantProductSearch\Dependency\Facade\MerchantProductSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantProductSearch\Dependency\Facade\MerchantProductSearchToProductPageSearchFacadeInterface;
use Spryker\Zed\MerchantProductSearch\Persistence\MerchantProductSearchRepositoryInterface;

class MerchantProductSearchWriter implements MerchantProductSearchWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductSearch\Dependency\Facade\MerchantProductSearchToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\MerchantProductSearch\Dependency\Facade\MerchantProductSearchToProductPageSearchFacadeInterface
     */
    protected $productPageSearchFacade;

    /**
     * @var \Spryker\Zed\MerchantProductSearch\Persistence\MerchantProductSearchRepositoryInterface
     */
    protected $merchantProductSearchRepository;

    /**
     * @param \Spryker\Zed\MerchantProductSearch\Dependency\Facade\MerchantProductSearchToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\MerchantProductSearch\Dependency\Facade\MerchantProductSearchToProductPageSearchFacadeInterface $productPageSearchFacade
     * @param \Spryker\Zed\MerchantProductSearch\Persistence\MerchantProductSearchRepositoryInterface $merchantProductSearchRepository
     */
    public function __construct(
        MerchantProductSearchToEventBehaviorFacadeInterface $eventBehaviorFacade,
        MerchantProductSearchToProductPageSearchFacadeInterface $productPageSearchFacade,
        MerchantProductSearchRepositoryInterface $merchantProductSearchRepository
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productPageSearchFacade = $productPageSearchFacade;
        $this->merchantProductSearchRepository = $merchantProductSearchRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByIdMerchantEvents(array $eventTransfers): void
    {
        $merchantIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        $productAbstractIds = $this->merchantProductSearchRepository->getProductAbstractIdsByMerchantIds($merchantIds);

        $this->productPageSearchFacade->refresh($productAbstractIds);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByIdMerchantProductAbstractEvents(array $eventTransfers): void
    {
        $merchantProductAbstractIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        $productAbstractIds = $this->merchantProductSearchRepository->getProductAbstractIdsByMerchantProductAbstractIds($merchantProductAbstractIds);

        $this->productPageSearchFacade->refresh($productAbstractIds);
    }
}
