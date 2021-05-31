<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\SellableItemBatchRequestTransfer;
use Generated\Shared\Transfer\SellableItemBatchResponseTransfer;
use Generated\Shared\Transfer\SellableItemRequestTransfer;
use Generated\Shared\Transfer\SellableItemResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface;

class Sellable implements SellableInterface
{
    /**
     * @var \Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface
     */
    protected $availabilityRepository;

    /**
     * @var \Spryker\Zed\Availability\Business\Model\AvailabilityHandlerInterface
     */
    protected $availabilityHandler;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\AvailabilityExtension\Dependency\Plugin\AvailabilityStrategyPluginInterface[]
     */
    protected $availabilityStrategyPlugins;

    /**
     * @var \Spryker\Zed\AvailabilityExtension\Dependency\Plugin\BatchAvailabilityStrategyPluginInterface[]
     */
    protected $batchAvailabilityStrategyPlugins;

    /**
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface $availabilityRepository
     * @param \Spryker\Zed\Availability\Business\Model\AvailabilityHandlerInterface $availabilityHandler
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\AvailabilityExtension\Dependency\Plugin\AvailabilityStrategyPluginInterface[] $availabilityStrategyPlugins
     * @param \Spryker\Zed\AvailabilityExtension\Dependency\Plugin\BatchAvailabilityStrategyPluginInterface[] $batchAvailabilityStrategyPlugins
     */
    public function __construct(
        AvailabilityRepositoryInterface $availabilityRepository,
        AvailabilityHandlerInterface $availabilityHandler,
        AvailabilityToStoreFacadeInterface $storeFacade,
        array $availabilityStrategyPlugins,
        array $batchAvailabilityStrategyPlugins
    ) {
        $this->availabilityRepository = $availabilityRepository;
        $this->availabilityHandler = $availabilityHandler;
        $this->storeFacade = $storeFacade;
        $this->availabilityStrategyPlugins = $availabilityStrategyPlugins;
        $this->batchAvailabilityStrategyPlugins = $batchAvailabilityStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemBatchRequestTransfer $sellableItemBatchRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemBatchResponseTransfer
     */
    public function areProductsSellableForStore(
        SellableItemBatchRequestTransfer $sellableItemBatchRequestTransfer
    ): SellableItemBatchResponseTransfer {
        $sellableItemBatchResponseTransfer = new SellableItemBatchResponseTransfer();
        $storeTransfer = $sellableItemBatchRequestTransfer->getStoreOrFail();

        foreach ($sellableItemBatchRequestTransfer->getSellableItemRequests() as $sellableItemRequestTransfer) {
            $sellableItemtResponseTransfer = $this->processSellableItemRequestForCustomProducts(
                $sellableItemRequestTransfer,
                $storeTransfer
            );
            if (!$sellableItemtResponseTransfer) {
                continue;
            }

            $sellableItemBatchResponseTransfer->addSellableItemResponse($sellableItemtResponseTransfer);
        }

        foreach ($this->batchAvailabilityStrategyPlugins as $batchAvailabilityStrategyPlugin) {
            $sellableItemBatchResponseTransfer = $batchAvailabilityStrategyPlugin->findItemsAvailabilityForStore(
                $sellableItemBatchRequestTransfer,
                $sellableItemBatchResponseTransfer
            );
        }

        return $sellableItemBatchResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemBatchRequestTransfer $sellableItemsBatchRequestTransfer
     * @param \Generated\Shared\Transfer\SellableItemBatchResponseTransfer $sellableItemsBatchResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemBatchResponseTransfer
     */
    public function areProductConcretesSellableForStore(
        SellableItemBatchRequestTransfer $sellableItemsBatchRequestTransfer,
        SellableItemBatchResponseTransfer $sellableItemsBatchResponseTransfer
    ): SellableItemBatchResponseTransfer {
        if (!count($sellableItemsBatchRequestTransfer->getSellableItemRequests())) {
            return $sellableItemsBatchResponseTransfer;
        }
        $sellableItemResponseTransfers = $this->processProductConcretesBatchRequest($sellableItemsBatchRequestTransfer);
        if (count($sellableItemResponseTransfers)) {
            $sellableItemResponseTransfers = new ArrayObject(
                array_merge(
                    $sellableItemsBatchResponseTransfer->getSellableItemResponses()->getArrayCopy(),
                    $sellableItemResponseTransfers
                )
            );
            $sellableItemsBatchResponseTransfer->setSellableItemResponses($sellableItemResponseTransfers);
        }

        return $sellableItemsBatchResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemRequestTransfer $sellableItemRequestTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemResponseTransfer|null
     */
    protected function processSellableItemRequestForCustomProducts(
        SellableItemRequestTransfer $sellableItemRequestTransfer,
        StoreTransfer $storeTransfer
    ): ?SellableItemResponseTransfer {
        $concreteSku = $sellableItemRequestTransfer->getSkuOrFail();

        /** @var \Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer $productAvailabilityCriteriaTransfer */
        $productAvailabilityCriteriaTransfer = $sellableItemRequestTransfer->getProductAvailabilityCriteria();
        foreach ($this->availabilityStrategyPlugins as $availabilityStrategyPlugin) {
            if (!$availabilityStrategyPlugin->isApplicable($concreteSku, $storeTransfer, $productAvailabilityCriteriaTransfer)) {
                continue;
            }
            /** @var \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer $customProductConcreteAvailability */
            $customProductConcreteAvailability = $availabilityStrategyPlugin->findProductConcreteAvailabilityForStore(
                $concreteSku,
                $storeTransfer,
                $productAvailabilityCriteriaTransfer
            );
            $sellableItemResponseTransfer = $this->getSellableItemResponseTransfer(
                $sellableItemRequestTransfer,
                $customProductConcreteAvailability
            );
            $sellableItemRequestTransfer->setIsProcessed(true);

            return $sellableItemResponseTransfer;
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemRequestTransfer $sellableItemRequestTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemResponseTransfer
     */
    protected function getSellableItemResponseTransfer(
        SellableItemRequestTransfer $sellableItemRequestTransfer,
        ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
    ): SellableItemResponseTransfer {
        $sellableItemResponseTransfer = new SellableItemResponseTransfer();
        $availableQuantity = $sellableItemRequestTransfer->getQuantityOrFail() ?? new Decimal(0);
        $sellableItemResponseTransfer->setSku($sellableItemRequestTransfer->getSku());
        $sellableItemResponseTransfer->setAvailableQuantity($productConcreteAvailabilityTransfer->getAvailability());
        $sellableItemResponseTransfer->setIsSellable($this->isProductConcreteSellable(
            $productConcreteAvailabilityTransfer,
            $availableQuantity
        ));

        return $sellableItemResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemBatchRequestTransfer $sellableItemBatchRequestTransfer
     *
     * @return string[]
     */
    protected function getSkus($sellableItemBatchRequestTransfer): array
    {
        $concreteSkus = [];
        foreach ($sellableItemBatchRequestTransfer->getSellableItemRequests() as $sellableItemRequest) {
            $concreteSkus[] = $sellableItemRequest->getSkuOrFail();
        }

        return $concreteSkus;
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemBatchRequestTransfer $sellableItemBatchRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemResponseTransfer[]
     */
    protected function processProductConcretesBatchRequest(
        SellableItemBatchRequestTransfer $sellableItemBatchRequestTransfer
    ): array {
        $sellableItemResponseTransfers = [];
        $storeTransfer = $sellableItemBatchRequestTransfer->getStoreOrFail();
        $concreteSkus = $this->getSkus($sellableItemBatchRequestTransfer);

        $productConcreteAvailabilityTransfers = $this->availabilityRepository
        ->findProductConcreteAvailabilityBySkusAndStore($concreteSkus, $storeTransfer);

        $productConcreteAvailabilityTransfersSkuMap = $this->getMappedProductConcreteAvailabilityTransfers($productConcreteAvailabilityTransfers);

        foreach ($sellableItemBatchRequestTransfer->getSellableItemRequests() as $sellableItemRequestTransfer) {
            if ($sellableItemRequestTransfer->getIsProcessed()) {
                continue;
            }
            $sellableItemRequestTransfer->setIsProcessed(true);
            $concreteSku = $sellableItemRequestTransfer->getSkuOrFail();
            $productConcreteAvailabilityTransfersExistForSku = array_key_exists($concreteSku, $productConcreteAvailabilityTransfersSkuMap);
            $productConcreteAvailabilityTransfer = null;
            if (!$productConcreteAvailabilityTransfersExistForSku) {
                $productConcreteAvailabilityTransfer = $this->availabilityHandler
                    ->updateProductConcreteAvailabilityBySku($concreteSku, $storeTransfer);
            }
            if ($productConcreteAvailabilityTransfersExistForSku) {
                $productConcreteAvailabilityTransfer = $productConcreteAvailabilityTransfersSkuMap[$concreteSku];
            }
            $sellableItemResponseTransfers[] = $this->getSellableItemResponseTransfer(
                $sellableItemRequestTransfer,
                $productConcreteAvailabilityTransfer
            );
        }

        return $sellableItemResponseTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer[] $productConcreteAvailabilityTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer[]
     */
    protected function getMappedProductConcreteAvailabilityTransfers(array $productConcreteAvailabilityTransfers): array
    {
        return array_reduce(
            $productConcreteAvailabilityTransfers,
            [$this, 'mapProductConcreteAvailabilityTransferBySku'],
            []
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer[] $productConcreteAvailabilityTransfersMap
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer[]
     */
    protected function mapProductConcreteAvailabilityTransferBySku(
        array $productConcreteAvailabilityTransfersMap,
        ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
    ): array {
        $productConcreteAvailabilityTransfersMap[$productConcreteAvailabilityTransfer->getSkuOrFail()] = $productConcreteAvailabilityTransfer;

        return $productConcreteAvailabilityTransfersMap;
    }

    /**
     * @param string $concreteSku
     * @param \Spryker\DecimalObject\Decimal $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer|null $productAvailabilityCriteriaTransfer
     *
     * @return bool
     */
    public function isProductSellableForStore(
        string $concreteSku,
        Decimal $quantity,
        StoreTransfer $storeTransfer,
        ?ProductAvailabilityCriteriaTransfer $productAvailabilityCriteriaTransfer = null
    ): bool {
        foreach ($this->availabilityStrategyPlugins as $availabilityStrategyPlugin) {
            if (!$availabilityStrategyPlugin->isApplicable($concreteSku, $storeTransfer, $productAvailabilityCriteriaTransfer)) {
                continue;
            }

            $customProductConcreteAvailability = $availabilityStrategyPlugin->findProductConcreteAvailabilityForStore($concreteSku, $storeTransfer, $productAvailabilityCriteriaTransfer);

            return $customProductConcreteAvailability
                ? $this->isProductConcreteSellable($customProductConcreteAvailability, $quantity)
                : false;
        }

        $storeTransfer = $this->assertStoreTransfer($storeTransfer);
        $productConcreteAvailabilityTransfer = $this->availabilityRepository
            ->findProductConcreteAvailabilityBySkuAndStore($concreteSku, $storeTransfer);

        if ($productConcreteAvailabilityTransfer === null) {
            $productConcreteAvailabilityTransfer = $this->availabilityHandler
                ->updateProductConcreteAvailabilityBySku($concreteSku, $storeTransfer);
        }

        return $this->isProductConcreteSellable($productConcreteAvailabilityTransfer, $quantity);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return bool
     */
    public function isProductConcreteAvailable(int $idProductConcrete): bool
    {
        $storeTransfer = $this->storeFacade->getCurrentStore();
        $productConcreteAvailabilityTransfer = $this->availabilityRepository
            ->findProductConcreteAvailabilityByIdProductConcreteAndStore($idProductConcrete, $storeTransfer);

        if ($productConcreteAvailabilityTransfer === null) {
            $productConcreteAvailabilityTransfer = $this->availabilityHandler
                ->updateProductConcreteAvailabilityById($idProductConcrete, $storeTransfer);
        }

        return $this->isProductConcreteSellable($productConcreteAvailabilityTransfer, new Decimal(0));
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null $productConcreteAvailabilityTransfer
     * @param \Spryker\DecimalObject\Decimal $quantity
     *
     * @return bool
     */
    protected function isProductConcreteSellable(
        ?ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer,
        Decimal $quantity
    ): bool {
        if ($productConcreteAvailabilityTransfer === null) {
            return false;
        }

        if ($productConcreteAvailabilityTransfer->getIsNeverOutOfStock()) {
            return true;
        }

        /** @var \Spryker\DecimalObject\Decimal $availability */
        $availability = $productConcreteAvailabilityTransfer->requireAvailability()->getAvailability();

        if ($quantity->isZero()) {
            return $availability->greaterThan($quantity);
        }

        return $availability->greatherThanOrEquals($quantity);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function assertStoreTransfer(StoreTransfer $storeTransfer): StoreTransfer
    {
        if ($storeTransfer->getIdStore() !== null) {
            return $storeTransfer;
        }

        /** @var string $storeName */
        $storeName = $storeTransfer->requireName()->getName();

        return $this->storeFacade->getStoreByName($storeName);
    }
}
