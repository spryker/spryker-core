<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\SellableItemRequestTransfer;
use Generated\Shared\Transfer\SellableItemResponseTransfer;
use Generated\Shared\Transfer\SellableItemsRequestTransfer;
use Generated\Shared\Transfer\SellableItemsResponseTransfer;
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
     * @var array<\Spryker\Zed\AvailabilityExtension\Dependency\Plugin\AvailabilityStrategyPluginInterface>
     */
    protected $availabilityStrategyPlugins;

    /**
     * @var array<\Spryker\Zed\AvailabilityExtension\Dependency\Plugin\BatchAvailabilityStrategyPluginInterface>
     */
    protected $batchAvailabilityStrategyPlugins;

    /**
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface $availabilityRepository
     * @param \Spryker\Zed\Availability\Business\Model\AvailabilityHandlerInterface $availabilityHandler
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface $storeFacade
     * @param array<\Spryker\Zed\AvailabilityExtension\Dependency\Plugin\AvailabilityStrategyPluginInterface> $availabilityStrategyPlugins
     * @param array<\Spryker\Zed\AvailabilityExtension\Dependency\Plugin\BatchAvailabilityStrategyPluginInterface> $batchAvailabilityStrategyPlugins
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
     * @param \Generated\Shared\Transfer\SellableItemsRequestTransfer $sellableItemsRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemsResponseTransfer
     */
    public function areProductsSellableForStore(
        SellableItemsRequestTransfer $sellableItemsRequestTransfer
    ): SellableItemsResponseTransfer {
        $sellableItemsResponseTransfer = new SellableItemsResponseTransfer();
        $sellableItemsRequestTransfer = $this->assertStoreTransferInSellableItemsRequestTransfer($sellableItemsRequestTransfer);

        $sellableItemsResponseTransfer = $this->processSellableItemsRequestSuccessively(
            $sellableItemsRequestTransfer,
            $sellableItemsResponseTransfer,
        );

        $sellableItemsResponseTransfer = $this->processSellableItemsRequestInBatch(
            $sellableItemsRequestTransfer,
            $sellableItemsResponseTransfer,
        );

        return $sellableItemsResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemsRequestTransfer $sellableItemsRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemsRequestTransfer
     */
    protected function assertStoreTransferInSellableItemsRequestTransfer(
        SellableItemsRequestTransfer $sellableItemsRequestTransfer
    ): SellableItemsRequestTransfer {
        $storeTransfer = $sellableItemsRequestTransfer->getStoreOrFail();
        $storeTransfer = $this->assertStoreTransfer($storeTransfer);
        $sellableItemsRequestTransfer->setStore($storeTransfer);

        return $sellableItemsRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemsRequestTransfer $sellableItemsRequestTransfer
     * @param \Generated\Shared\Transfer\SellableItemsResponseTransfer $sellableItemsResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemsResponseTransfer
     */
    protected function processSellableItemsRequestInBatch(
        SellableItemsRequestTransfer $sellableItemsRequestTransfer,
        SellableItemsResponseTransfer $sellableItemsResponseTransfer
    ): SellableItemsResponseTransfer {
        foreach ($this->batchAvailabilityStrategyPlugins as $batchAvailabilityStrategyPlugin) {
            $sellableItemsResponseTransfer = $batchAvailabilityStrategyPlugin->findItemsAvailabilityForStore(
                $sellableItemsRequestTransfer,
                $sellableItemsResponseTransfer,
            );
        }

        return $sellableItemsResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemsRequestTransfer $sellableItemsRequestTransfer
     * @param \Generated\Shared\Transfer\SellableItemsResponseTransfer $sellableItemsResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemsResponseTransfer
     */
    protected function processSellableItemsRequestSuccessively(
        SellableItemsRequestTransfer $sellableItemsRequestTransfer,
        SellableItemsResponseTransfer $sellableItemsResponseTransfer
    ): SellableItemsResponseTransfer {
        $storeTransfer = $sellableItemsRequestTransfer->getStoreOrFail();
        foreach ($sellableItemsRequestTransfer->getSellableItemRequests() as $sellableItemRequestTransfer) {
            $sellableItemResponseTransfer = $this->processSellableItemRequestForCustomProducts(
                $sellableItemRequestTransfer,
                $storeTransfer,
            );
            if (!$sellableItemResponseTransfer) {
                continue;
            }

            $sellableItemsResponseTransfer->addSellableItemResponse($sellableItemResponseTransfer);
        }

        return $sellableItemsResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemsRequestTransfer $sellableItemsRequestTransfer
     * @param \Generated\Shared\Transfer\SellableItemsResponseTransfer $sellableItemsResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemsResponseTransfer
     */
    public function areProductConcretesSellableForStore(
        SellableItemsRequestTransfer $sellableItemsRequestTransfer,
        SellableItemsResponseTransfer $sellableItemsResponseTransfer
    ): SellableItemsResponseTransfer {
        if (!count($sellableItemsRequestTransfer->getSellableItemRequests())) {
            return $sellableItemsResponseTransfer;
        }
        $sellableItemResponseTransfers = $this->processProductConcretesBatchRequest($sellableItemsRequestTransfer);
        if (count($sellableItemResponseTransfers)) {
            $sellableItemResponseTransfers = new ArrayObject(
                array_merge(
                    $sellableItemsResponseTransfer->getSellableItemResponses()->getArrayCopy(),
                    $sellableItemResponseTransfers,
                ),
            );
            $sellableItemsResponseTransfer->setSellableItemResponses($sellableItemResponseTransfers);
        }

        return $sellableItemsResponseTransfer;
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

            $customProductConcreteAvailability = $availabilityStrategyPlugin->findProductConcreteAvailabilityForStore(
                $concreteSku,
                $storeTransfer,
                $productAvailabilityCriteriaTransfer,
            );

            $sellableItemResponseTransfer = $this->getSellableItemResponseTransfer(
                $sellableItemRequestTransfer,
                $customProductConcreteAvailability,
            );
            $sellableItemRequestTransfer->setIsProcessed(true);

            return $sellableItemResponseTransfer;
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemRequestTransfer $sellableItemRequestTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null $productConcreteAvailabilityTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemResponseTransfer
     */
    protected function getSellableItemResponseTransfer(
        SellableItemRequestTransfer $sellableItemRequestTransfer,
        ?ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
    ): SellableItemResponseTransfer {
        $sellableItemResponseTransfer = new SellableItemResponseTransfer();
        $availableQuantity = $sellableItemRequestTransfer->getQuantityOrFail();
        $sellableItemResponseTransfer->setSku($sellableItemRequestTransfer->getSku());
        $sellableItemResponseTransfer->setAvailableQuantity(0);

        if ($productConcreteAvailabilityTransfer) {
            $sellableItemResponseTransfer->setAvailableQuantity($productConcreteAvailabilityTransfer->getAvailability());
        }

        $sellableItemResponseTransfer->setIsSellable($this->isProductConcreteSellable(
            $productConcreteAvailabilityTransfer,
            $availableQuantity,
        ));

        return $sellableItemResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemsRequestTransfer $sellableItemsRequestTransfer
     *
     * @return array<string>
     */
    protected function getSkus($sellableItemsRequestTransfer): array
    {
        $concreteSkus = [];
        foreach ($sellableItemsRequestTransfer->getSellableItemRequests() as $sellableItemRequest) {
            $concreteSkus[] = $sellableItemRequest->getSkuOrFail();
        }

        return $concreteSkus;
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemsRequestTransfer $sellableItemsRequestTransfer
     *
     * @return array<\Generated\Shared\Transfer\SellableItemResponseTransfer>
     */
    protected function processProductConcretesBatchRequest(
        SellableItemsRequestTransfer $sellableItemsRequestTransfer
    ): array {
        $sellableItemResponseTransfers = [];
        $storeTransfer = $sellableItemsRequestTransfer->getStoreOrFail();
        $concreteSkus = $this->getSkus($sellableItemsRequestTransfer);

        $productConcreteAvailabilityTransfers = $this->availabilityRepository
            ->findProductConcreteAvailabilityBySkusAndStore($concreteSkus, $storeTransfer);

        $productConcreteAvailabilityTransfersSkuMap = $this->getMappedProductConcreteAvailabilityTransfers($productConcreteAvailabilityTransfers);

        foreach ($sellableItemsRequestTransfer->getSellableItemRequests() as $sellableItemRequestTransfer) {
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
                $productConcreteAvailabilityTransfer,
            );
        }

        return $sellableItemResponseTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer> $productConcreteAvailabilityTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer>
     */
    protected function getMappedProductConcreteAvailabilityTransfers(array $productConcreteAvailabilityTransfers): array
    {
        return array_reduce(
            $productConcreteAvailabilityTransfers,
            [$this, 'mapProductConcreteAvailabilityTransferBySku'],
            [],
        );
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer> $productConcreteAvailabilityTransfersMap
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer>
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
