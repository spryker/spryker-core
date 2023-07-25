<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Business\Builder;

use Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer;
use Generated\Shared\Transfer\StoreConditionsTransfer;
use Generated\Shared\Transfer\StoreCriteriaTransfer;
use Spryker\Zed\ProductOfferAvailabilityStorage\Business\Reader\StoreReaderInterface;

class ProductOfferAvailabilityRequestBuilder implements ProductOfferAvailabilityRequestBuilderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferAvailabilityStorage\Business\Reader\StoreReaderInterface
     */
    protected StoreReaderInterface $storeReader;

    /**
     * @param \Spryker\Zed\ProductOfferAvailabilityStorage\Business\Reader\StoreReaderInterface $storeReader
     */
    public function __construct(StoreReaderInterface $storeReader)
    {
        $this->storeReader = $storeReader;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer> $productOfferAvailabilityRequestTransfers
     * @param array<int, list<int>> $storeIdsGroupedByIdStock
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer>
     */
    public function buildProductOfferAvailabilityRequestsWithStore(
        array $productOfferAvailabilityRequestTransfers,
        array $storeIdsGroupedByIdStock
    ): array {
        $productOfferAvailabilityRequestTransfersWithStore = [];
        $storeTransfersIndexedByIdStore = $this->storeReader->getStoreTransfersIndexedByIdStore(
            (new StoreCriteriaTransfer())->setStoreConditions(
                (new StoreConditionsTransfer())->setStoreIds(
                    $this->extractStoreIdsFromStoreIdsGroupedByIdStock($storeIdsGroupedByIdStock),
                ),
            ),
        );

        foreach ($productOfferAvailabilityRequestTransfers as $productOfferAvailabilityRequestTransfer) {
            $productOfferAvailabilityRequestTransfersWithStore = $this->addProductOfferAvailabilityRequestTransfersWithStore(
                $storeIdsGroupedByIdStock,
                $productOfferAvailabilityRequestTransfer,
                $storeTransfersIndexedByIdStore,
                $productOfferAvailabilityRequestTransfersWithStore,
            );
        }

        return $productOfferAvailabilityRequestTransfersWithStore;
    }

    /**
     * @param array<int, list<int>> $storeIdsGroupedByIdStock
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
     * @param array<int, \Generated\Shared\Transfer\StoreTransfer> $storeTransfersIndexedByIdStore
     * @param list<\Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer> $productOfferAvailabilityRequestTransfersWithStore
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer>
     */
    protected function addProductOfferAvailabilityRequestTransfersWithStore(
        array $storeIdsGroupedByIdStock,
        ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer,
        array $storeTransfersIndexedByIdStore,
        array $productOfferAvailabilityRequestTransfersWithStore
    ): array {
        foreach ($storeIdsGroupedByIdStock[$productOfferAvailabilityRequestTransfer->getStockOrFail()->getIdStockOrFail()] as $idStore) {
            $productOfferAvailabilityRequestTransfersWithStore[] = (new ProductOfferAvailabilityRequestTransfer())
                ->fromArray($productOfferAvailabilityRequestTransfer->toArray(), true)
                ->setStore($storeTransfersIndexedByIdStore[$idStore]);
        }

        return $productOfferAvailabilityRequestTransfersWithStore;
    }

    /**
     * @param array<int, list<int>> $storeIdsGroupedByIdStock
     *
     * @return list<int>
     */
    protected function extractStoreIdsFromStoreIdsGroupedByIdStock(array $storeIdsGroupedByIdStock): array
    {
        /** @var list<int> $storeIds */
        $storeIds = [];

        foreach ($storeIdsGroupedByIdStock as $idStockStore) {
            $storeIds = array_merge($storeIds, $idStockStore);
        }

        return $storeIds;
    }
}
