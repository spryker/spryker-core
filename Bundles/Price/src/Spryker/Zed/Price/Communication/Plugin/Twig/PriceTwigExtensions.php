<?php

namespace Spryker\Zed\Price\Communication\Plugin\Twig;

use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Zed\Price\Communication\Plugin\Twig\Filters\PriceFilter;

class PriceTwigExtensions extends \Twig_Extension
{

    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('currency', function ($price) {
                $currencyManager = CurrencyManager::getInstance();
                $priceFilter = new PriceFilter($currencyManager);

                return $priceFilter->getConvertedPrice($price);
            }, [
                'is_safe' => ['html'],
            ]),
        ];
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'PriceTwigExtensions';
    }

}
