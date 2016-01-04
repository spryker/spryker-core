<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Stock\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Stock\Persistence\StockQueryContainer;
use Spryker\Zed\Stock\StockConfig;

/**
 * @method StockQueryContainer getQueryContainer()
 * @method StockConfig getConfig()
 */
class StockCommunicationFactory extends AbstractCommunicationFactory
{

}
