<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Availability\Dependency\Facade;

use Spryker\Zed\Stock\Business\StockFacade;

class AvailabilityToStockBridge implements AvailabilityToStockInterface
{

    /**
     * @var StockFacade
     */
    protected $stockFacade;

    /**
     * @param \Spryker\Zed\Stock\Business\StockFacade $stockFacade
     */
    public function __construct($stockFacade)
    {
        $this->stockFacade = $stockFacade;
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    public function calculateStockForProduct($sku)
    {
        return $this->stockFacade->calculateStockForProduct($sku);
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function isNeverOutOfStock($sku)
    {
        return $this->stockFacade->isNeverOutOfStock($sku);
    }

}
