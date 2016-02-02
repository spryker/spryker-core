<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\PriceCartConnector\Dependency\Facade;

use Spryker\Zed\Price\Business\PriceFacade;

class PriceCartToPriceBridge implements PriceCartToPriceInterface
{

    /**
     * @var PriceFacade
     */
    protected $priceFacade;

    /**
     * @param \Spryker\Zed\Price\Business\PriceFacade $priceFacade
     */
    public function __construct($priceFacade)
    {
        $this->priceFacade = $priceFacade;
    }

    /**
     * @param string $sku
     * @param null $priceType
     *
     * @return bool
     */
    public function hasValidPrice($sku, $priceType = null)
    {
        return $this->priceFacade->hasValidPrice($sku, $priceType);
    }

    /**
     * @param string $sku
     * @param null $priceType
     *
     * @return int
     */
    public function getPriceBySku($sku, $priceType = null)
    {
        return $this->priceFacade->getPriceBySku($sku, $priceType);
    }

}
