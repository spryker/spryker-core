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
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsInterface $omsFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface $stockFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        AvailabilityToOmsInterface $omsFacade,
        AvailabilityToStockInterface $stockFacade,
        AvailabilityToStoreFacadeInterface $storeFacade
    ) {
        $this->omsFacade = $omsFacade;
        $this->stockFacade = $stockFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param string $sku
     * @param int $quantity
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
     * @return int
     */
    public function calculateStockForProduct($sku)
    {
        $storeTransfer = $this->storeFacade->getCurrentStore();

        return $this->calculateStock($sku, $storeTransfer);
    }

    /**
     * @param string $sku
     * @param int $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    public function isProductSellableForStore($sku, $quantity, StoreTransfer $storeTransfer)
    {
        return $this->calculateIsProductSellable($sku, $quantity, $storeTransfer);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return int
     */
    public function calculateStockForProductWithStore($sku, StoreTransfer $storeTransfer)
    {
        return $this->calculateStock($sku, $storeTransfer);
    }

    /**
     * @param string $sku
     * @param int $quantity
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
     * @return int
     */
    protected function calculateStock($sku, StoreTransfer $storeTransfer)
    {
        $physicalItems = $this->stockFacade->calculateProductStockForStore($sku, $storeTransfer);
        $reservedItems = $this->omsFacade->getOmsReservedProductQuantityForSku($sku, $storeTransfer);

        return $physicalItems - $reservedItems;
    }
}
