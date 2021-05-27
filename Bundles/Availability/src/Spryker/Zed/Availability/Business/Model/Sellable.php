<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\SellableProductRequestItemTransfer;
use Generated\Shared\Transfer\SellableProductResponseItemTransfer;
use Generated\Shared\Transfer\SellableProductsBatchRequestTransfer;
use Generated\Shared\Transfer\SellableProductsBatchResponseTransfer;
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
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface $availabilityRepository
     * @param \Spryker\Zed\Availability\Business\Model\AvailabilityHandlerInterface $availabilityHandler
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\AvailabilityExtension\Dependency\Plugin\AvailabilityStrategyPluginInterface[] $availabilityStrategyPlugins
     */
    public function __construct(
        AvailabilityRepositoryInterface $availabilityRepository,
        AvailabilityHandlerInterface $availabilityHandler,
        AvailabilityToStoreFacadeInterface $storeFacade,
        array $availabilityStrategyPlugins
    ) {
        $this->availabilityRepository = $availabilityRepository;
        $this->availabilityHandler = $availabilityHandler;
        $this->storeFacade = $storeFacade;
        $this->availabilityStrategyPlugins = $availabilityStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\SellableProductsBatchRequestTransfer $sellableProductsBatchRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SellableProductsBatchResponseTransfer
     */
    public function areProductsSellableForStore(
        SellableProductsBatchRequestTransfer $sellableProductsBatchRequestTransfer
    ): SellableProductsBatchResponseTransfer {
        $sellableProductConcretesBatchRequestTransfer = new SellableProductsBatchRequestTransfer();
        $sellableProductsBatchResponseTransfer = new SellableProductsBatchResponseTransfer();
        $storeTransfer = $sellableProductsBatchRequestTransfer->getStoreOrFail();
        $sellableProductConcretesBatchRequestTransfer->setStore($storeTransfer);
        $sellableProductConcreteResponseItemTransfers = [];

        foreach ($sellableProductsBatchRequestTransfer->getSellableProductRequestItems() as $sellableProductRequestItemTransfer) {
            $sellableProductResponseItemTransfer = $this->processSellableProductRequestItemForCustomProducts(
                $sellableProductRequestItemTransfer,
                $storeTransfer
            );
            if(!$sellableProductResponseItemTransfer) {
                $sellableProductConcretesBatchRequestTransfer->addSellableProductRequestItems($sellableProductRequestItemTransfer);
                continue;
            }

            $sellableProductsBatchResponseTransfer->addSellableProductResponseItems($sellableProductResponseItemTransfer);
        }

        return $this->processSellableProductRequestItemForProductConcrete(
            $sellableProductConcretesBatchRequestTransfer,
            $sellableProductsBatchResponseTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SellableProductsBatchRequestTransfer $sellableProductConcretesBatchRequestTransfer
     * @param \Generated\Shared\Transfer\SellableProductsBatchResponseTransfer $sellableProductsBatchResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SellableProductsBatchResponseTransfer
     */
    protected function processSellableProductRequestItemForProductConcrete(
        SellableProductsBatchRequestTransfer $sellableProductConcretesBatchRequestTransfer,
        SellableProductsBatchResponseTransfer $sellableProductsBatchResponseTransfer
    ): SellableProductsBatchResponseTransfer {
        if (count($sellableProductConcretesBatchRequestTransfer->getSellableProductRequestItems())) {
            return $sellableProductsBatchResponseTransfer;
        }
        $sellableProductConcreteResponseItemTransfers = $this->processProductConcretesBatchRequest($sellableProductConcretesBatchRequestTransfer);
        if (count($sellableProductConcreteResponseItemTransfers)) {
            $sellableProductResponseItemTransfers = new ArrayObject(
                array_merge(
                    $sellableProductsBatchResponseTransfer->getSellableProductResponseItems()->getArrayCopy(),
                    $sellableProductConcreteResponseItemTransfers
                )
            );
            $sellableProductsBatchResponseTransfer->setSellableProductResponseItems($totalSesponseItems);
        }

        return $sellableProductsBatchResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SellableProductRequestItemTransfer $sellableProductRequestItemTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\SellableProductResponseItemTransfer|null
     */
    protected function processSellableProductRequestItemForCustomProducts(
        SellableProductRequestItemTransfer $sellableProductRequestItemTransfer,
        StoreTransfer $storeTransfer
    ): ?SellableProductResponseItemTransfer {
        /** @var string $concreteSku */
        $concreteSku = $sellableProductRequestItemTransfer->getSkuOrFail();

        /** @var \Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer $productAvailabilityCriteriaTransfer */
        $productAvailabilityCriteriaTransfer = $sellableProductRequestItemTransfer->getProductAvailabilityCriteria();
        foreach ($this->availabilityStrategyPlugins as $availabilityStrategyPlugin) {
            if (!$availabilityStrategyPlugin->isApplicable(
                $concreteSku,
                $storeTransfer,
                $productAvailabilityCriteriaTransfer
            )) {
                continue;
            }
            /** @var \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer $customProductConcreteAvailability */
            $customProductConcreteAvailability = $availabilityStrategyPlugin->findProductConcreteAvailabilityForStore(
                $concreteSku,
                $storeTransfer,
                $productAvailabilityCriteriaTransfer
            );
            /** @var \Generated\Shared\Transfer\SellableProductResponseItemTransfer $sellableProductResponseItemTransfer */
            $sellableProductResponseItemTransfer = $this->getSellableProductResponseItemTransfer(
                $sellableProductRequestItemTransfer,
                $customProductConcreteAvailability
            );

            return $sellableProductResponseItemTransfer;
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\SellableProductRequestItemTransfer $sellableProductRequestItemTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
     *
     * @return \Generated\Shared\Transfer\SellableProductResponseItemTransfer
     */
    protected function getSellableProductResponseItemTransfer(
        SellableProductRequestItemTransfer $sellableProductRequestItemTransfer,
        ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
    ): SellableProductResponseItemTransfer {
        $sellableProductResponseItemTransfer = new SellableProductResponseItemTransfer();
        $availableQuantity = $sellableProductRequestItemTransfer->getQuantityOrFail() ?? new Decimal(0);
        $sellableProductResponseItemTransfer->setSku($sellableProductRequestItemTransfer->getSku());
        $sellableProductResponseItemTransfer->setAvailableQuantity($productConcreteAvailabilityTransfer->getAvailability());
        $sellableProductResponseItemTransfer->setIsSellable($this->isProductConcreteSellable(
            $productConcreteAvailabilityTransfer,
            $availableQuantity
        ));

        return $sellableProductResponseItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SellableProductsBatchRequestTransfer $sellableProductBatchRequestTransfer
     *
     * @return array
     */
    protected function getSkus($sellableProductBatchRequestTransfer): array
    {
        foreach ($sellableProductBatchRequestTransfer->getSellableProductRequestItems() as $sellableProductRequestItem) {
            $sku = $sellableProductRequestItem->getSkuOrFail();
            $concreteSkus[] = $sku;
        }

        return $concreteSkus;
    }

    /**
     * @param \Generated\Shared\Transfer\SellableProductsBatchRequestTransfer $sellableProductConcretesBatchRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SellableProductResponseItemTransfer[]
     */
    protected function processProductConcretesBatchRequest(
        SellableProductsBatchRequestTransfer $sellableProductConcretesBatchRequestTransfer
    ): array {
        $sellableProductResponseItemTransfers = [];
        $storeTransfer = $sellableProductConcretesBatchRequestTransfer->getStoreOrFail();
        $concreteSkus = $this->getSkus($sellableProductConcretesBatchRequestTransfer);
        $productConcreteAvailabilityTransfers = $this->availabilityRepository
        ->findProductConcreteAvailabilityBySkusAndStore($concreteSkus, $storeTransfer);
        $productConcreteAvailabilityTransfersSkuMap = $this->getProductConcreteAvailabilityTransfersSkuMap($productConcreteAvailabilityTransfers);

        foreach ($sellableProductConcretesBatchRequestTransfer->getSellableProductRequestItems() as $sellableProductRequestItemTransfer) {
            $concreteSku = $sellableProductRequestItemTransfer->getSkuOrFail();
            $productConcreteAvailabilityTransfersExistForSku = array_key_exists($concreteSku, $productConcreteAvailabilityTransfersSkuMap);
            $productConcreteAvailabilityTransfer = null;
            if (!$productConcreteAvailabilityTransfersExistForSku) {
                $productConcreteAvailabilityTransfer = $this->availabilityHandler
                    ->updateProductConcreteAvailabilityBySku($concreteSku, $storeTransfer);
            }
            if ($productConcreteAvailabilityTransfersExistForSku) {
                $productConcreteAvailabilityTransfer = $productConcreteAvailabilityTransfersSkuMap[$concreteSku];
            }
            $sellableProductResponseItemTransfers[] = $this->getSellableProductResponseItemTransfer(
                $sellableProductRequestItemTransfer,
                $productConcreteAvailabilityTransfer
            );
        }

        return $sellableProductResponseItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer[] $productConcreteAvailabilityTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer[]
     */
    protected function getProductConcreteAvailabilityTransfersSkuMap(array $productConcreteAvailabilityTransfers): array
    {
        return array_reduce(
            $productConcreteAvailabilityTransfers,
            [$this, 'mapProductConcreteAvailabilityTransferBySku'],
            []
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer[] $ProductConcreteAvailabilityTransfersMap
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer[]
     */
    protected function mapProductConcreteAvailabilityTransferBySku(
        array $ProductConcreteAvailabilityTransfersMap,
        ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer): array {

        return $ProductConcreteAvailabilityTransfersMap[$productConcreteAvailabilityTransfer->getSkuOrFail()] = $productConcreteAvailabilityTransfer;
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
