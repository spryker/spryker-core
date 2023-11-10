<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\Writer;

use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferStorageFacadeInterface;
use Spryker\Zed\MerchantProductOfferStorage\MerchantProductOfferStorageConfig;
use Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageRepositoryInterface;

/**
 * @method \Spryker\Zed\MerchantProductOfferStorage\Business\MerchantProductOfferStorageBusinessFactory getFactory()
 */
class ProductOfferStorageWriter implements ProductOfferStorageWriterInterface
{
    /**
     * @var int
     */
    protected const MIN_ID_PRODUCT_OFFER = 0;

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageRepositoryInterface
     */
    protected $merchantProductOfferStorageRepository;

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferStorageFacadeInterface
     */
    protected $productOfferStorageFacade;

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\MerchantProductOfferStorageConfig
     */
    protected MerchantProductOfferStorageConfig $merchantProductOfferStorageConfig;

    /**
     * @param \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageRepositoryInterface $merchantProductOfferStorageRepository
     * @param \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferStorageFacadeInterface $productOfferStorageFacade
     * @param \Spryker\Zed\MerchantProductOfferStorage\MerchantProductOfferStorageConfig $merchantProductOfferStorageConfig
     */
    public function __construct(
        MerchantProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        MerchantProductOfferStorageRepositoryInterface $merchantProductOfferStorageRepository,
        MerchantProductOfferStorageToProductOfferStorageFacadeInterface $productOfferStorageFacade,
        MerchantProductOfferStorageConfig $merchantProductOfferStorageConfig
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->merchantProductOfferStorageRepository = $merchantProductOfferStorageRepository;
        $this->productOfferStorageFacade = $productOfferStorageFacade;
        $this->merchantProductOfferStorageConfig = $merchantProductOfferStorageConfig;
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByMerchantEvents(array $eventTransfers): void
    {
        $merchantIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);
        if (!$merchantIds) {
            return;
        }

        $productOfferReferenceIterator = $this->merchantProductOfferStorageRepository->iterateProductOfferReferencesByMerchantIds(
            $merchantIds,
            static::MIN_ID_PRODUCT_OFFER,
            $this->merchantProductOfferStorageConfig->getWriteCollectionByMerchantEventsBatchSize(),
        );
        foreach ($productOfferReferenceIterator as $productOfferReferences) {
            $eventTransfers = [];
            foreach ($productOfferReferences as $productOfferReference) {
                $eventTransfers[] = (new EventEntityTransfer())
                    ->setAdditionalValues([SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => $productOfferReference]);
            }

            $this->productOfferStorageFacade->writeProductOfferStorageCollectionByProductOfferEvents($eventTransfers);
        }
    }
}
