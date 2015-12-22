<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductSearch\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainer;
use Spryker\Zed\ProductSearch\ProductSearchConfig;

/**
 * @method ProductSearchQueryContainer getQueryContainer()
 * @method ProductSearchConfig getConfig()
 */
class ProductSearchCommunicationFactory extends AbstractCommunicationFactory
{
}
