<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Currency;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Shared\Currency\Builder\CurrencyBuilder;

class CurrencyFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Shared\Currency\Builder\CurrencyBuilderInterface
     */
    public function createCurrencyBuilder()
    {
        return new CurrencyBuilder(
            $this->getIntlCurrencyBundle(),
            $this->getStore()->getCurrencyIsoCode()
        );
    }

    /**
     * @return \Symfony\Component\Intl\ResourceBundle\CurrencyBundleInterface
     */
    protected function getIntlCurrencyBundle()
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::INTL_CURRENCY_BUNDLE);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::STORE);
    }

}
