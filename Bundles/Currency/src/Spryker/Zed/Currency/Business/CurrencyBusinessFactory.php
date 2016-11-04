<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Business;

use Spryker\Shared\Currency\Builder\CurrencyBuilder;
use Spryker\Zed\Currency\CurrencyDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

class CurrencyBusinessFactory extends AbstractBusinessFactory
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
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::STORE);
    }

    /**
     * @return \Symfony\Component\Intl\ResourceBundle\CurrencyBundleInterface
     */
    protected function getIntlCurrencyBundle()
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::INTL_CURRENCY_BUNDLE);
    }

}
