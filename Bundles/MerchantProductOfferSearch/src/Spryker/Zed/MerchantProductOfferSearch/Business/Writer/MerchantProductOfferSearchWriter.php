<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Business\Writer;

use Spryker\Zed\MerchantProductOfferSearch\Dependency\Facade\MerchantProductOfferSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantProductOfferSearch\Dependency\Facade\MerchantProductOfferSearchToProductPageSearchFacadeInterface;
use Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepositoryInterface;

class MerchantProductOfferSearchWriter implements MerchantProductOfferSearchWriterInterface
{
    /**
     * @uses \Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferStoreTableMap::COL_FK_PRODUCT_OFFER
     *
     * @var string
     */
    protected const FK_PRODUCT_OFFER = 'spy_product_offer_store.fk_product_offer';

    /**
     * @var \Spryker\Zed\MerchantProductOfferSearch\Dependency\Facade\MerchantProductOfferSearchToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\MerchantProductOfferSearch\Dependency\Facade\MerchantProductOfferSearchToProductPageSearchFacadeInterface
     */
    protected $pageSearchFacade;

    /**
     * @var \Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepositoryInterface
     */
    protected $merchantProductOfferSearchRepository;

    /**
     * @param \Spryker\Zed\MerchantProductOfferSearch\Dependency\Facade\MerchantProductOfferSearchToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\MerchantProductOfferSearch\Dependency\Facade\MerchantProductOfferSearchToProductPageSearchFacadeInterface $pageSearchFacade
     * @param \Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepositoryInterface $merchantProductOfferSearchRepository
     */
    public function __construct(
        MerchantProductOfferSearchToEventBehaviorFacadeInterface $eventBehaviorFacade,
        MerchantProductOfferSearchToProductPageSearchFacadeInterface $pageSearchFacade,
        MerchantProductOfferSearchRepositoryInterface $merchantProductOfferSearchRepository
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->pageSearchFacade = $pageSearchFacade;
        $this->merchantProductOfferSearchRepository = $merchantProductOfferSearchRepository;
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByIdMerchantEvents(array $eventTransfers): void
    {
        $merchantIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        $productAbstractIds = $this->merchantProductOfferSearchRepository->getProductAbstractIdsByMerchantIds($merchantIds);

        $this->pageSearchFacade->refresh($productAbstractIds);
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByIdProductOfferEvents(array $eventTransfers): void
    {
        $productOfferIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        $productAbstractIds = $this->merchantProductOfferSearchRepository->getProductAbstractIdsByProductOfferIds($productOfferIds);

        $this->pageSearchFacade->refresh($productAbstractIds);
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeProductConcreteCollectionByIdProductOfferEvents(array $eventTransfers): void
    {
        $productOfferIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        $productIds = $this->merchantProductOfferSearchRepository->getProductConcreteIdsByProductOfferIds($productOfferIds);

        $this->pageSearchFacade->publishProductConcretes($productIds);
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeProductConcreteCollectionByIdProductOfferStoreEvents(array $eventTransfers): void
    {
        $productOfferIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventTransfers,
            static::FK_PRODUCT_OFFER,
        );

        $productIds = $this->merchantProductOfferSearchRepository->getProductConcreteIdsByProductOfferIds($productOfferIds);

        $this->pageSearchFacade->publishProductConcretes($productIds);
    }
}
