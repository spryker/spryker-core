<?php

namespace SprykerFeature\Yves\Price\Plugin;

use SprykerFeature\Shared\Library\Currency\CurrencyManager;
use Silex\Application;
use SprykerEngine\Yves\Kernel\AbstractPlugin;
use SprykerFeature\Yves\Twig\Dependency\Plugin\TwigFilterPluginInterface;
use SprykerFeature\Yves\Twig\Dependency\Plugin\TwigFunctionPluginInterface;

class TwigPricePlugin extends AbstractPlugin implements TwigFilterPluginInterface, TwigFunctionPluginInterface
{
    /**
     * @return \Twig_SimpleFilter[]
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('price', function ($priceValue, $withSymbol = true) {
                $priceValue = CurrencyManager::getInstance()->convertCentToDecimal($priceValue);

                return CurrencyManager::getInstance()->format($priceValue, $withSymbol);
            }),
            new \Twig_SimpleFilter('priceCeil', function ($priceValue, $withSymbol = false) {
                $priceValue = CurrencyManager::getInstance()->ceil(
                    CurrencyManager::getInstance()->convertCentToDecimal($priceValue),
                    0
                );

                return $priceValue;
            }),
            new \Twig_SimpleFilter('priceFloor', function ($priceValue, $withSymbol = false) {
                $priceValue = floor(CurrencyManager::getInstance()->convertCentToDecimal($priceValue));

                return $priceValue;
            }),
        ];
    }

    /**
     * @param Application $application
     *
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions(Application $application)
    {
        return [
            new \Twig_SimpleFunction('getItemTotalPrice', function ($grossPrice, $quantity = 1) {
               return $grossPrice * $quantity;
            })
        ];
    }
}
