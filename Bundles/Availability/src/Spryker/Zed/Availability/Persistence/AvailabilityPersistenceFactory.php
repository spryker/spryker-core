<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Persistence;

use Orm\Zed\Availability\Persistence\SpyAvailabilityQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Availability\AvailabilityConfig getConfig()
 * @method \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer getQueryContainer()
 */
class AvailabilityPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return SpyAvailabilityQuery
     */
    public function createSpyAvailabilityQuery()
    {
        return SpyAvailabilityQuery::create();
    }
}
