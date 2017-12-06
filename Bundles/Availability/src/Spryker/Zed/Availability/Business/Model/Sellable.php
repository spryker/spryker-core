<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business\Model;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface;
use Spryker\Zed\Store\Business\StoreFacade;

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
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsInterface $omsFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface $stockFacade
     */
    public function __construct(
        AvailabilityToOmsInterface $omsFacade,
        AvailabilityToStockInterface $stockFacade
    ) {
        $this->omsFacade = $omsFacade;
        $this->stockFacade = $stockFacade;
    }

    /**
     * @param string $sku
     * @param int $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return bool
     */
    public function isProductSellable($sku, $quantity, StoreTransfer $storeTransfer = null)
    {
        if (!$storeTransfer) {
            $storeTransfer = (new StoreFacade())->getCurrentStore();
        }

        if ($this->stockFacade->isNeverOutOfStock($sku, $storeTransfer)) {
            return true;
        }
        $realStock = $this->calculateStockForProduct($sku, $storeTransfer);

        return ($realStock >= $quantity);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return int
     */
    public function calculateStockForProduct($sku, StoreTransfer $storeTransfer = null)
    {
        if (!$storeTransfer) {
            $storeTransfer = (new StoreFacade())->getCurrentStore();
        }

        $physicalItems = $this->stockFacade->calculateProductStockForStore($sku, $storeTransfer);

        $reservedItems = $this->omsFacade->getOmsReservedProductQuantityForSku($sku, $storeTransfer);

        return $physicalItems - $reservedItems;
    }
}
