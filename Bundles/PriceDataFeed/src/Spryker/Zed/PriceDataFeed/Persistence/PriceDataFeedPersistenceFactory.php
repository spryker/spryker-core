<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceDataFeed\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\PriceDataFeed\PriceDataFeedDependencyProvider;

/**
 * @method \Spryker\Zed\PriceDataFeed\PriceDataFeedConfig getConfig()
 * @method \Spryker\Zed\PriceDataFeed\Persistence\PriceDataFeedQueryContainer getQueryContainer()
 */
class PriceDataFeedPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\PriceDataFeed\Dependency\QueryContainer\PriceDataFeedToPriceInterface
     */
    public function getPriceQueryContainer()
    {
        return $this->getProvidedDependency(PriceDataFeedDependencyProvider::PRICE_QUERY_CONTAINER);
    }
}
