<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Price;

use Spryker\Client\Price\PriceModeResolver\PriceModeResolver;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Yves\Price\PriceModeSwitcher\PriceModeSwitcher;

/**
 * @method \Spryker\Yves\Price\PriceConfig getConfig()
 */
class PriceFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Yves\Price\PriceModeSwitcher\PriceModeSwitcherInterface
     */
    public function createPriceModeSwitcher()
    {
        return new PriceModeSwitcher(
            $this->getQuoteClient(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Yves\Price\Dependency\Client\PriceToQuoteClientInterface
     */
    public function getQuoteClient()
    {
        return $this->getProvidedDependency(PriceDependencyProvider::CLIENT_QUOTE);
    }

}
