<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Communication\Plugin\Twig\Filters;

use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Price\Business\PriceFacade getFacade()
 * @method \Spryker\Zed\Price\Communication\PriceCommunicationFactory getFactory()
 */
class PriceFilter extends AbstractPlugin
{

    /**
     * @deprecated
     */
    const DECIMALS = 2;

    /**
     * @deprecated
     */
    const DIVIDER = 100;

    /**
     * @var \Spryker\Shared\Library\Currency\CurrencyManager
     */
    protected $currencyManager;

    /**
     * @param \Spryker\Shared\Library\Currency\CurrencyManager $currencyManager
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
        $moneyPlugin = $this->getFactory()->getMoneyPlugin();
        $moneyTransfer = $moneyPlugin->getMoney($price);

        return $moneyPlugin->formatWithSymbol($moneyTransfer);
    }

}
