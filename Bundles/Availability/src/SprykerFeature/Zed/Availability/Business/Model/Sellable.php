<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Availability\Business\Model;

use SprykerFeature\Zed\Oms\Business\OmsFacade;
use SprykerFeature\Zed\Stock\Business\StockFacade;

class Sellable implements SellableInterface
{

    /**
     * @var OmsFacade
     */
    protected $omsFacade;
    /**
     * @var StockFacade
     */
    protected $stockFacade;

    /**
     * @param OmsFacade $omsFacade
     * @param StockFacade $stockFacade
     */
    public function __construct(
        OmsFacade $omsFacade,
        StockFacade $stockFacade
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
