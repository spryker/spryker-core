<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Price\PriceDependencyProvider;

/**
 * @method \Spryker\Zed\Price\PriceConfig getConfig()
 * @method \Spryker\Zed\Price\Persistence\PriceQueryContainer getQueryContainer()
 */
class PriceCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\Money\Communication\Plugin\MoneyPluginInterface
     */
    public function getMoneyPlugin()
    {
        return $this->getProvidedDependency(PriceDependencyProvider::PLUGIN_MONEY);
    }

}
