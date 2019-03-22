<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business\Model;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface;
use Spryker\Zed\Availability\Dependency\Service\AvailabilityToUtilQuantityServiceInterface;

class Sellable implements SellableInterface
{
    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsInterface
     */
    protected $omsFacade;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface
     */
    protected $stockFacade;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Service\AvailabilityToUtilQuantityServiceInterface
     */
    protected $utilQuantityService;

    /**
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsInterface $omsFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface $stockFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\Availability\Dependency\Service\AvailabilityToUtilQuantityServiceInterface $utilQuantityService
     */
    public function __construct(
        AvailabilityToOmsInterface $omsFacade,
        AvailabilityToStockInterface $stockFacade,
        AvailabilityToStoreFacadeInterface $storeFacade,
        AvailabilityToUtilQuantityServiceInterface $utilQuantityService
    ) {
        $this->omsFacade = $omsFacade;
        $this->stockFacade = $stockFacade;
        $this->storeFacade = $storeFacade;
        $this->utilQuantityService = $utilQuantityService;
    }

    /**
     * @param string $sku
     * @param float $quantity
     *
     * @return bool
     */
    public function isProductSellable($sku, $quantity)
    {
        $storeTransfer = $this->storeFacade->getCurrentStore();

        return $this->calculateIsProductSellable($sku, $quantity, $storeTransfer);
    }

    /**
     * @param string $sku
     *
     * @return float
     */
    public function calculateStockForProduct($sku)
    {
        $storeTransfer = $this->storeFacade->getCurrentStore();

        return $this->calculateStock($sku, $storeTransfer);
    }

    /**
     * @param string $sku
     * @param float $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    public function isProductSellableForStore($sku, $quantity, StoreTransfer $storeTransfer)
    {
        return $this->calculateIsProductSellable($sku, $quantity, $storeTransfer);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return bool
     */
    public function isProductConcreteAvailable(int $idProductConcrete): bool
    {
        $stockProductTransfers = $this->stockFacade->getStockProductsByIdProduct($idProductConcrete);
        if (empty($stockProductTransfers)) {
            return false;
        }

        $storeTransfer = $this->storeFacade->getCurrentStore();

        return $this->calculateIsProductSellable($stockProductTransfers[0]->getSku(), 1.0, $storeTransfer);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return float
     */
    public function calculateStockForProductWithStore($sku, StoreTransfer $storeTransfer)
    {
        return $this->calculateStock($sku, $storeTransfer);
    }

    /**
     * @param string $sku
     * @param float $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    protected function calculateIsProductSellable($sku, $quantity, StoreTransfer $storeTransfer)
    {
        if ($this->stockFacade->isNeverOutOfStockForStore($sku, $storeTransfer)) {
            return true;
        }

        $realStock = $this->calculateStock($sku, $storeTransfer);

        return ($realStock >= $quantity);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return float
     */
    protected function calculateStock($sku, StoreTransfer $storeTransfer)
    {
        $physicalItems = $this->stockFacade->calculateProductStockForStore($sku, $storeTransfer);
        $reservedItems = $this->omsFacade->getOmsReservedProductQuantityForSku($sku, $storeTransfer);

        return $this->subtractQuantities($physicalItems, $reservedItems);
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return float
     */
    protected function subtractQuantities(float $firstQuantity, float $secondQuantity): float
    {
        return $this->utilQuantityService->subtractQuantities($firstQuantity, $secondQuantity);
    }
}
