<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Currency;

use Spryker\Shared\Currency\Builder\CurrencyBuilder;
use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\AbstractFactory;
use Symfony\Component\Intl\Intl;

class CurrencyFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Shared\Currency\Builder\CurrencyBuilderInterface
     */
    public function createCurrencyBuilder()
    {
        return new CurrencyBuilder(
            Intl::getCurrencyBundle(),
            Store::getInstance()->getCurrencyIsoCode()
        );
    }

}
