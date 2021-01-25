<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business\Model;

use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;
use Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface;

class ProductAvailabilityReader implements ProductAvailabilityReaderInterface
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
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\AvailabilityExtension\Dependency\Plugin\AvailabilityStrategyPluginInterface[]
     */
    protected $availabilityStrategyPlugins;

    /**
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface $availabilityRepository
     * @param \Spryker\Zed\Availability\Business\Model\AvailabilityHandlerInterface $availabilityHandler
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\AvailabilityExtension\Dependency\Plugin\AvailabilityStrategyPluginInterface[] $availabilityStrategyPlugins
     */
    public function __construct(
        AvailabilityRepositoryInterface $availabilityRepository,
        AvailabilityHandlerInterface $availabilityHandler,
        AvailabilityToStoreFacadeInterface $storeFacade,
        AvailabilityToProductFacadeInterface $productFacade,
        array $availabilityStrategyPlugins
    ) {
        $this->availabilityRepository = $availabilityRepository;
        $this->availabilityHandler = $availabilityHandler;
        $this->storeFacade = $storeFacade;
        $this->productFacade = $productFacade;
        $this->availabilityStrategyPlugins = $availabilityStrategyPlugins;
    }

    /**
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer|null
     */
    public function findOrCreateProductAbstractAvailabilityBySkuForStore(
        string $abstractSku,
        StoreTransfer $storeTransfer
    ): ?ProductAbstractAvailabilityTransfer {
        $storeTransfer = $this->assertStoreTransfer($storeTransfer);
        $productAbstractAvailabilityTransfer = $this->availabilityRepository
            ->findProductAbstractAvailabilityBySkuAndStore($abstractSku, $storeTransfer);

        if ($productAbstractAvailabilityTransfer === null) {
            return $this->updateProductAbstractAvailabilityBySku($abstractSku, $storeTransfer);
        }

        return $productAbstractAvailabilityTransfer;
    }

    /**
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer|null
     */
    protected function updateProductAbstractAvailabilityBySku(
        string $abstractSku,
        StoreTransfer $storeTransfer
    ): ?ProductAbstractAvailabilityTransfer {
        if (!$this->productFacade->hasProductAbstract($abstractSku)) {
            return null;
        }

        return $this->availabilityHandler
            ->updateProductAbstractAvailabilityBySku($abstractSku, $storeTransfer);
    }

    /**
     * @param string $concreteSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer|null $productAvailabilityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    public function findOrCreateProductConcreteAvailabilityBySkuForStore(
        string $concreteSku,
        StoreTransfer $storeTransfer,
        ?ProductAvailabilityCriteriaTransfer $productAvailabilityCriteriaTransfer = null
    ): ?ProductConcreteAvailabilityTransfer {
        $storeTransfer = $this->assertStoreTransfer($storeTransfer);

        foreach ($this->availabilityStrategyPlugins as $availabilityStockProviderStrategyPlugin) {
            if (!$availabilityStockProviderStrategyPlugin->isApplicable($concreteSku, $storeTransfer, $productAvailabilityCriteriaTransfer)) {
                continue;
            }

            return $availabilityStockProviderStrategyPlugin->findProductConcreteAvailabilityForStore($concreteSku, $storeTransfer, $productAvailabilityCriteriaTransfer);
        }

        $productConcreteAvailabilityTransfer = $this->availabilityRepository
            ->findProductConcreteAvailabilityBySkuAndStore($concreteSku, $storeTransfer);

        if ($productConcreteAvailabilityTransfer === null) {
            return $this->updateProductConcreteAvailabilityBySku($concreteSku, $storeTransfer);
        }

        return $productConcreteAvailabilityTransfer;
    }

    /**
     * @param string $concreteSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    protected function updateProductConcreteAvailabilityBySku(
        string $concreteSku,
        StoreTransfer $storeTransfer
    ): ?ProductConcreteAvailabilityTransfer {
        if (!$this->productFacade->hasProductConcrete($concreteSku)) {
            return null;
        }

        return $this->availabilityHandler
            ->updateProductConcreteAvailabilityBySku($concreteSku, $storeTransfer);
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

        $storeTransfer
            ->requireName();

        return $this->storeFacade->getStoreByName($storeTransfer->getName());
    }
}
