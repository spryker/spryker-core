<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityDataFeed\Persistence;

use Spryker\Zed\AvailabilityDataFeed\AvailabilityDataFeedDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\AvailabilityDataFeed\AvailabilityDataFeedConfig getConfig()
 * @method \Spryker\Zed\AvailabilityDataFeed\Persistence\AvailabilityDataFeedQueryContainerInterface getQueryContainer()
 */
class AvailabilityDataFeedPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\AvailabilityDataFeed\Dependency\QueryContainer\AvailabilityDataFeedToAvailabilityInterface
     */
    public function getAvailabilityQueryContainer()
    {
        return $this->getProvidedDependency(AvailabilityDataFeedDependencyProvider::AVAILABILITY_QUERY_CONTAINER);
    }
}
