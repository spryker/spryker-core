<?php

namespace SprykerFeature\Zed\Price\Communication\Plugin\Twig\Filters;

use SprykerFeature\Shared\Library\Currency\CurrencyManager;

class PriceFilter
{

    const DECIMALS = 2;

    const DIVIDER = 100;

    protected $currencyManager;

    /**
     * @param CurrencyManager $currencyManager
     */
    public function __construct(CurrencyManager $currencyManager)
    {
        $this->currencyManager = $currencyManager;
    }

    /**
     * @param int $price
     *
     * @return string
     */
    public function getConvertedPrice($price)
    {
        return $this->currencyManager->format(
            $this->currencyManager->convertCentToDecimal($price)
        );
    }

}
