<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Communication\Plugin\Twig;

use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Shared\Twig\TwigExtension;
use Spryker\Shared\Twig\TwigFilter;
use Spryker\Zed\Price\Communication\Plugin\Twig\Filters\PriceFilter;

/**
 * @deprecated Use `TwigMoneyServiceProvider` instead.
 */
class PriceTwigExtension extends TwigExtension
{

    /**
     * @return \Spryker\Shared\Twig\TwigFilter[]
     */
    public function getFilters()
    {
        return [
            new TwigFilter('currency', function ($price) {
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
