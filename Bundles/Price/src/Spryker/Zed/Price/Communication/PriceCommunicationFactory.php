<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Price\Communication;

use Spryker\Zed\Price\Persistence\PriceQueryContainer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Price\PriceConfig;

/**
 * @method PriceConfig getConfig()
 * @method PriceQueryContainer getQueryContainer()
 */
class PriceCommunicationFactory extends AbstractCommunicationFactory
{
}
