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
use Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageRepositoryInterface;

class ProductConcreteOffersStorageWriter implements ProductConcreteOffersStorageWriterInterface
{
    /**
     * @var int
     */
    protected const PRODUCT_OFFER_EVENT_BATCH_SIZE = 200;

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
     * @param \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageRepositoryInterface $merchantProductOfferStorageRepository
     * @param \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferStorageFacadeInterface $productOfferStorageFacade
     */
    public function __construct(
        MerchantProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        MerchantProductOfferStorageRepositoryInterface $merchantProductOfferStorageRepository,
        MerchantProductOfferStorageToProductOfferStorageFacadeInterface $productOfferStorageFacade
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->merchantProductOfferStorageRepository = $merchantProductOfferStorageRepository;
        $this->productOfferStorageFacade = $productOfferStorageFacade;
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

        foreach ($this->getProductConcreteSkusByMerchantIdsBatch($merchantIds) as $eventTransfersWithSkuBatch) {
            $this->productOfferStorageFacade->writeProductConcreteProductOffersStorageCollectionByProductEvents($eventTransfersWithSkuBatch);
        }
    }

    /**
     * @param array<int> $merchantIds
     *
     * @return iterable
     */
    protected function getProductConcreteSkusByMerchantIdsBatch(array $merchantIds): iterable
    {
        $eventTransfersWithSku = [];

        $generator = $this->merchantProductOfferStorageRepository->getProductConcreteSkusByMerchantIds($merchantIds);

        foreach ($generator as $productSkuCollection) {
            foreach ($productSkuCollection as $productSku) {
                $eventTransfersWithSku[] = (new EventEntityTransfer())
                    ->setAdditionalValues([SpyProductOfferTableMap::COL_CONCRETE_SKU => $productSku]);

                if (count($eventTransfersWithSku) === static::PRODUCT_OFFER_EVENT_BATCH_SIZE) {
                    yield $eventTransfersWithSku;

                    $eventTransfersWithSku = [];
                }
            }
        }

        if ($eventTransfersWithSku) {
            yield $eventTransfersWithSku;
        }
    }
}
