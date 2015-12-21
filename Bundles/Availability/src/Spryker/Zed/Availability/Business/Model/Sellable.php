<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Availability\Business\Model;

use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface;

class Sellable implements SellableInterface
{

    /**
     * @var AvailabilityToOmsInterface
     */
    protected $omsFacade;

    /**
     * @var AvailabilityToStockInterface
     */
    protected $stockFacade;

    /**
     * @param AvailabilityToOmsInterface $omsFacade
     * @param AvailabilityToStockInterface $stockFacade
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
     *
     * @return bool
     */
    public function isProductSellable($sku, $quantity)
    {
        if ($this->stockFacade->isNeverOutOfStock($sku)) {
            return true;
        }
        $realStock = $this->calculateStockForProduct($sku);

        return ($realStock >= $quantity);
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    public function calculateStockForProduct($sku)
    {
        $physicalItems = $this->stockFacade->calculateStockForProduct($sku);
        $reservedItems = $this->omsFacade->countReservedOrderItemsForSku($sku);

        return $physicalItems - $reservedItems;
    }

}
