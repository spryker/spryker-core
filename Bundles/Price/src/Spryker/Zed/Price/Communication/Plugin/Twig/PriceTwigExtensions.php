<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Communication\Plugin\Twig;

use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Zed\Price\Communication\Plugin\Twig\Filters\PriceFilter;

/**
 * @deprecated Use PriceTwigExtension class instead.
 */
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
