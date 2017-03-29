<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityDataFeed\Persistence;

use Spryker\Zed\DataFeed\DataFeedDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\AvailabilityDataFeed\AvailabilityDataFeedConfig getConfig()
 * @method \Spryker\Zed\AvailabilityDataFeed\Persistence\AvailabilityDataFeedQueryContainer getQueryContainer()
 */
class AvailabilityDataFeedPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface
     */
    public function getStockQueryContainer()
    {
        return $this->getProvidedDependency(DataFeedDependencyProvider::STOCK_QUERY_CONTAINER);
    }

}
